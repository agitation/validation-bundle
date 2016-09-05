<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable;

use Agit\BaseBundle\Exception\InternalErrorException;
use ReflectionClass;

trait PluginInstanceFactoryTrait
{
    use ServiceInjectorTrait;

    protected function createInstance($class, $plugin)
    {
        if (is_object($class)) {
            $instance = $class;
        } else {
            if (! class_exists($class)) {
                throw new InternalErrorException("Invalid plugin class.");
            }

            $classRefl = new ReflectionClass($class);
            $instance = $classRefl->newInstanceWithoutConstructor();
            $this->injectServices($instance, $plugin->get("depends"));
        }

        $this->checkInterface($instance);

        return $instance;
    }

    abstract protected function checkInterface($instance);
}
