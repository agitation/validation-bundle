<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\Strategy;

use Agit\CommonBundle\Exception\InternalErrorException;

trait PluginInstanceFactoryTrait
{
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

            $classRefl = new \ReflectionClass($class);
            $instance = $classRefl->newInstanceWithoutConstructor();
            $dependencies = $plugin->get('depends');

            if (is_array($dependencies) && count($dependencies))
            {
                if (!$this->getContainer())
                    throw new InternalErrorException("The $class plugin needs the services container.");

                if (!($instance instanceof ServiceAwarePluginInterface))
                    throw new InternalErrorException("The $class plugin has defined dependencies and thus must implement the ServiceAwarePluginInterface.");

                foreach ($dependencies as $serviceName)
                    $instance->setService($serviceName, $this->getContainer()->get($serviceName));
            }
        }

        $this->checkInterface($instance);

        return $instance;
    }

    abstract protected function getContainer();

    abstract protected function checkInterface($instance);

}
