<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\Strategy;

use ReflectionClass;
use Agit\BaseBundle\Exception\InternalErrorException;

trait PluginInstanceFactoryTrait
{
    use ServiceInjectorTrait;

    protected function createInstance($class, $plugin)
    {
        if (is_object($class))
        {
            $instance = $class;
        }
        else
        {
            if (!class_exists($class))
                throw new InternalErrorException("Invalid plugin class.");

            $classRefl = new ReflectionClass($class);
            $instance = $classRefl->newInstanceWithoutConstructor();
            $this->injectServices($instance, $plugin->get("depends"));
        }

        $this->checkInterface($instance);

        return $instance;
    }

    abstract protected function checkInterface($instance);
}
