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
use Agit\CoreBundle\Exception\InternalErrorException;
use Agit\CoreBundle\Service\ClassCollector;
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
        if (!is_array($attributes) || !isset($attributes['type']) || !isset($attributes['tag']))
            throw new InternalErrorException("The tag attributes are invalid.");

        $tag = $attributes['tag'];
        $type = $attributes['type'];
        unset($attributes['type']);

        if (!isset($this->serviceTags[$tag]))
            $this->serviceTags[$tag] = [];

        $this->serviceTags[$tag][] = $this->getProcessorFactory($type)->createPluggableService($attributes);
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

                foreach ($pluginTags[$pluggableService->get('tag')] as $pluginClass => $pluginAnnotation)
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

            $classList = $this->classCollector->collect($pluginPath);

            foreach ($classList as $class)
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
                    $tag = $plugin->get('tag');

                    // parents may have their own dependencies. we support that!
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

    // gets all annotations from a class, its traits, its parents and their traits (in this order)
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
