**Agitation** is an e-commerce framework, based on Symfony2, focussed on
extendability through plugged-in APIs, UIs, payment modules and other
components.

## AgitCoreBundle

The AgitCoreBundle is the basic bundle for all Agitation components. It defines
some abstract classes and conventions for all Agitation-based components.

### Pluggability

But, more importantly, it provides the pluggability infrastructure. It works as
follows:

- Bundles can define pluggable components.
- If a service is pluggable, it can be extended by other bundles.
- Unlike the Symfony event mechanisms, this doesn't happen at run-time, but during cache-warming.
- Plugins can be loaded at runtime from the cache.

Take, for example, the [https://www.github.com/agitation/AgitValidationBundle](AgitValidationBundle):

The bundle defines the `agit.validation` service. At the same time, it accepts
the registration of additional validators. It uses the *object “strategy”*, which
means that it declares a certain parent object (`AbstractValidator`), and awaits
the registration of validator child objects.

The logic for the registration is provided by this AgitCoreBundle; pluggable
services as well as the plugins to the services can simply select a registration
processing strategy and set it up through service configuration. Or they can
extend the basic strategies for more advanced extendibility scenarios.

### Fixtures

The pluggability features also allow managing “fixtures”, i.e. entity values
which are to be inserted into the database at the time of bundle installation or
update.

A good example is the [https://www.github.com/agitation/AgitLocaleDataBundle](AgitLocaleDataBundle):
It provides (among others) an entity classes for countries, and when the plugin loader is run,
it will populate the database with all known countries from the CLDR repository.
