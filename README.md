# AgitPluggableBundle

This is a bundle for the [Agitation Framework components](https://www.github.com/agitation), based on Symfony.

This bundle provides strategies for throw-in pluggable third party components on a service level. It follows the “convention over configuration” approach, as far as possible. What does that mean?

Imagine a validation service. It has some methods to validate strings, integers, objects and other types of data. Now imagine you want to extend this service with a validator for HTML documents. With plain Symfony, you would create an event listener and register the new validator at run-time. Or you would allow registering extensions with the container builder. All of this requires you to write some configurational code.

Now imagine a large application where different types of services can be extended by third-party bundles. **Every single extension** needs an entry in a service definition or the bundle configuration. That’s really *a lot* of glue for just a bunch of pieces.


This bundle, however, allows services to accept plugins through tagging.

- Each *pluggable service* definition has an extra tag where the `name` is `agit.pluggable`. It also needs a `tag` for identification. ¹
- The *plugins* shipped by third party bundles reside in the `Plugin` folder. They carry a simple annotation telling us to which *pluggable service* tag they are subscribed.

¹ Yes, it is a bit confusing that we have to define a *tag* inside a *tag*, but there is no other suitable term for this (and `id` is already used in a different context).

The `agit:pluggable:process` command collects the plugins and registers them. At run-time, a pluggable service can get a list of registered plugins and use them. This command should be called once during the deployment of an application, or during an upgrade.


## Strategies

There are four plugin *strategies*:

- Cache
- Object
- Seed
- Entity

The strategy to be used is determined by the pluggable service. The annotation used by a plugin must be compatible to the strategy of the service, otherwise the registration will fail. Annotations have different fields, depending on the strategy and the information required for processing.

If a plugin has service dependencies, it must define them either with the `depends` field of its `…Plugin` annotation or the `@Depends` annotation. It also must implement the `ServiceAwarePluginInterface`, for example using the `ServiceAwarePluginTrait`.

### Cache

The *cache* strategy is simple. When the processing command runs, it will ask each plugin to deliver a list of cache entries. These are stored in a persistent cache and provided to the requesting service at run-time.

The config tag of a *cache* pluggable service must use the fields defined in the `CachePluggableService` class.

A *cache* plugin must use the `CachePlugin` annotation and implement the `CachePluginInterface`.

### Object

With the *object* strategy, each plugin is a class which will be registered with its full name. It also can define dependencies. When the service requests a plugin class at run-time, an object instance will be created and, if necessary, dependencies are injected.

The config tag of an *object* pluggable service must use the fields defined in the `ObjectPluggableService` class.

An *object* plugin must use the `ObjectPlugin` annotation. The pluggable service has to define a `baseClass` from which the plugin class has to inherit.

### Seed

The *seed* strategy allows inserting database entries for a certain entity. Imagine your application needs a list of currencies in the database. Creating a *seed* plugin you can make sure these entries are inserted when installing the database. And you can even have the entries updated when there are updates. The *seed* strategy is able to identifies entries is able to update them when necessary.

The *seed* strategy doesn’t have/need pluggable services. Instead, there’s a meta service, determining all available entities and looking for *seed* plugins that want to add database entries for the available entities.

A *seed* plugin must use the `SeedPlugin` annotation and implement the `SeedPluginInterface`.

**Attention:**

- As each seed entry represents a database entry, it needs an ID.
- We prefer *natural keys* for seeds, but entities with generated keys are supported, too. However, you must always pass a fixed ID value, because otherwise, updates wouldn’t work and we’d create duplicates.
- Composite keys are not supported.
- The seed processor is able to remove database entries for which seeds were not provided, to make sure that no obsolete database entries are available. It will ALWAYS do this automatically for entities with *natural* keys, but NEVER for entities with *generated* keys. The reason is that we assume (a) that entities with natural keys are only provided through seeds and (b) that entities with *generated* keys are user-managed.

### Entity

The *entity* strategy can be imagined as a combination of the *object* and the *seed* strategies: It allows registering an object which will be injected into a service on demand (similar to the *object* strategy), but it can create database seeds as well, and it is bound to a database entry, defined by the entity class and the plugin’s ID. Just like the *seed* strategy, the processor recognizes already existing database entries and will update an existing entry on subsequent calls. The same rules about natural and generated keys apply.

An *entity* plugin must use the `EntityPlugin` annotation and implement the `EntityPluginInterface`. The pluggable service has to define a `baseClass` from which the plugin class has to inherit.

## Examples

### Pluggable service

A service can be marked as pluggable with an extra tag in the service definition:

```Foo/BarBundle/Resources/config/services.yml
services:
    foo.bar.foobar_service:
        class: Foo\BarBundle\FoobarService
        tags:
            - { name: agit.pluggable, type: cache, tag: foo.bar.foobar, validator: Foo\BarBundle\FoobarPluginValidator }]
            # see CachePluggableService for all available keys beside 'name', 'type' and 'tag'
```

Now plugins can subscribe to this service, using the `CachePlugin` annotation, using the `foo.bar.foobar` *tag*.

When the service wants to access the registered plugin entries at run-time, it has to demand the injection of the a loader factory service, in this case `@agit.pluggable.cache.loader_factory`. The loader factory service will create a *loader* service which will load and provide the plugins to the service. First, inject the loader factory service into the pluggable service:

```Foo/BarBundle/Resources/config/services.yml
        …
        arguments: [ @agit.pluggable.cache.loader_factory ]
        …
```

In the constructor of the pluggable service, get the instance of the loader:

```Foo/BarBundle/FoobarService.php
<?php

namespace Foo\BarBundle;

use Agit\PluggableBundle\Strategy\Cache\CacheLoaderFactory;

class FoobarService
{
    $pluginEntries = [];

    public function __construct(CacheLoaderFactory $cacheLoaderFactory)
    {
        $this->pluginEntries = $cacheLoaderFactory->create("foo.bar.foobar")->load();
    }
}
```

We can also define a validator which will process plugin entries before they are stored:

```Foo/BarBundle/FoobarPluginValidator.php
<?php

namespace Foo\BarBundle;

use Agit\PluggableBundle\Strategy\Cache\CacheEntry;
use Agit\PluggableBundle\Strategy\Cache\CachePluginValidatorInterface;
use Agit\CoreBundle\Exception\InternalErrorException;

class FoobarPluginValidator implements CachePluginValidatorInterface
{
    private $expectedKeys = ["a", "b", "c"];

    public function validateEntry(CacheEntry $cacheEntry)
    {
        $keys = array_keys($cacheEntry->getData());

        foreach ($keys as $key)
            if (!in_array($key, $this->expectedKeys))
                throw new InternalErrorException("The array is missing the mandatory $key field.");
    }
}
```

### Plugin

A *cache* plugin would look like this:

```Foo/OtherBundle/Plugin/FoobarPlugin.php
<?php

namespace Foo\OtherBundle\Plugin;

use Agit\PluggableBundle\Strategy\Cache\CacheEntry;
use Agit\PluggableBundle\Strategy\Cache\CachePlugin;
use Agit\PluggableBundle\Strategy\Cache\CachePluginInterface;

/**
 * @CachePlugin(tag="foo.bar.foobar")
 */
class FoobarPlugin implements CachePluginInterface
{
    private $entryList;

    public function load()
    {
        foreach ($this->getList() as $key => $item)
        {
            $cacheEntry = new CacheEntry();
            $cacheEntry->setId($key);
            $cacheEntry->setData($item);
            $this->entryList[] = $cacheEntry;
        }
    }

    public function nextCacheEntry()
    {
        return array_shift($this->entryList);
    }

    private function getList()
    {
        return [
            "fruit" => [ "a" => "apple", "b" => "banana", "c" => "carrot" ],
            "vegetable" => [ "a" => "avocado", "b" => "beetroot", "c" => "cucumber" ]
        ];
    }
}
```

## Final thoughts

All of this is certainly not trivial, and keeping in mind that Symfony already has some nice features for extending bundles and services, this may seem to be a bit “over the top” for most scenarios.

However, for a application which is complex and wants to allow third-party extensions at the same time, it is (in our humble opinion) a valid approach to reduce the configurational overhead of the vanilla Symfony tools.
