<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\Service;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpKernel\Kernel;
use Agit\CommonBundle\Exception\InternalErrorException;
use Agit\CommonBundle\Service\ClassCollector;
use Agit\PluggableBundle\Strategy\ProcessorFactoryInterface;
use Agit\PluggableBundle\Strategy\PluggableServiceInterface;
use Agit\PluggableBundle\Strategy\PluginInterface;
use Agit\PluggableBundle\Strategy\Depends;

class ProcessorService
{
    private $kernel;

    private $annotationReader;

    private $classCollector;

    private $processorFactoryService;

    private $processorFactories = [];

    private $serviceTags = [];

    public function __construct(Kernel $kernel, Reader $annotationReader, ClassCollector $classCollector)
    {
        $this->kernel = $kernel;
        $this->annotationReader = $annotationReader;
        $this->classCollector = $classCollector;
    }

    public function addProcessorFactory($type, ProcessorFactoryInterface $instance)
    {
        $this->processorFactories[$type] = $instance;
    }

    public function addPluggableService($attributes)
    {
        if (!is_array($attributes) || !isset($attributes['type']))
            throw new InternalErrorException("The tag attributes are invalid.");

        $type = $attributes['type'];
        unset($attributes['type']);

        $pluggableService = $this->getProcessorFactory($type)->createPluggableService($attributes);

        $tag = $pluggableService->getTag();

        if (!isset($this->serviceTags[$tag]))
            $this->serviceTags[$tag] = [];

        $this->serviceTags[$tag][] = $pluggableService;
    }

    public function processPlugins()
    {
        $pluginTags = $this->getTagPlugins();

        foreach ($this->serviceTags as $tag => $pluggableServices)
        {
            if (!isset($pluginTags[$tag])) continue;

            foreach ($pluggableServices as $pluggableService)
            {
                $processor = $this->getProcessorFactory($pluggableService->getType())->createProcessor($pluggableService);

                foreach ($pluginTags[$tag] as $pluginClass => $pluginAnnotation)
                    $processor->addPlugin($pluginClass, $pluginAnnotation);

                $processor->process();
            }
        }
    }

    private function getTagPlugins()
    {
        $plugins = [];

        foreach ($this->kernel->getBundles() as $bundle)
        {
            $pluginPath = realpath($bundle->getPath() . '/Plugin');

            if (!$pluginPath) continue;

            // NOTE: It is possible that a plugin class extends a non-existent
            // other class. This is not unusual, because a bundle may
            // ship optional plugins, which are ignored if a matching
            // pluggable component is not present.
            //
            // This is why we simply ignore "broken" classes when collecting.

            $classes = $this->classCollector->collect($pluginPath, true);

            foreach ($classes as $class)
            {
                $annotations = $this->getAllAnnotations($class);

                $plugin = null;
                $depends = null;

                foreach ($annotations as $annotation)
                {
                    if (!$plugin && $annotation instanceof PluginInterface)
                        $plugin = $annotation;

                    if (!$depends && $annotation instanceof Depends)
                        $depends = $annotation;
                }

                if ($plugin)
                {
                    $tag = null;

                    if ($plugin->has('tag'))
                    {
                        $tag = $plugin->get('tag');
                    }
                    else
                    {
                        // if the strategy is tag-less (i.e. class based), we will try to find a
                        // matching (parent) class from the registered services
                        $pluginClass = get_class($plugin);

                        while (!$tag && class_exists($pluginClass))
                        {
                            if (isset($this->serviceTags[$pluginClass]))
                            {
                                $tag = $pluginClass;
                                break;
                            }

                            $pluginClass = get_parent_class($pluginClass);
                        }
                    }

                    if (!$tag) continue;

                    // add inherited dependecies
                    if ($depends)
                        $plugin->set('depends', array_merge($plugin->get('depends'), $depends->get('value')));

                    if (!isset($plugins[$tag]))
                        $plugins[$tag] = [];

                    $plugins[$tag][$class] = $plugin;
                }
            }
        }

        return $plugins;
    }

    private function getProcessorFactory($type)
    {
        if (!isset($this->processorFactories[$type]))
            throw new InternalErrorException("A processor $type has not been registered.");

        return $this->processorFactories[$type];
    }

    // getList all annotations from a class, its traits, its parents and their traits (in this order)
    private function getAllAnnotations($class)
    {
        $classRefl = new \ReflectionClass($class);
        $classes = [$class => $classRefl];
        $annotations = [];

        while ($classRefl = $classRefl->getParentClass())
        {
            $classes[$classRefl->getName()] = $classRefl;
        }

        foreach ($classes as $classRefl)
        {
            $annotations = array_merge($annotations, $this->annotationReader->getClassAnnotations($classRefl));

            foreach ($classRefl->getTraits() as $traitRefl)
                $annotations = array_merge($annotations, $this->annotationReader->getClassAnnotations($traitRefl));
        }

        return $annotations;
    }
}
