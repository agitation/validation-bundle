<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\Strategy\Entity;

use Agit\PluggableBundle\Strategy\Seed\SimpleSeedPlugin;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Agit\BaseBundle\Exception\InternalErrorException;
use Agit\PluggableBundle\Strategy\ProcessorInterface;
use Agit\PluggableBundle\Strategy\PluginInterface;
use Agit\PluggableBundle\Strategy\PluggableServiceInterface;
use Agit\PluggableBundle\Strategy\PluginInstanceFactoryTrait;
use Agit\PluggableBundle\Strategy\Object\ObjectPluggableService;
use Agit\PluggableBundle\Strategy\Object\ObjectProcessorFactory;
use Agit\PluggableBundle\Strategy\Object\ObjectPlugin;
use Agit\PluggableBundle\Strategy\Seed\SeedProcessorFactory;
use Agit\PluggableBundle\Strategy\Seed\SeedPluggableService;
use Agit\PluggableBundle\Strategy\Seed\SeedPlugin;

/**
 * This processor is mostly a combination of the seed and object processors.
 */
class EntityProcessor implements ProcessorInterface
{
    use PluginInstanceFactoryTrait;

    private $baseClass;

    private $container;

    private $objectProcessor;

    private $seedProcessor;

    private $pluggableService;

    private $registrationTag;

    private $plugins = [];

    public function __construct(
        ObjectProcessorFactory $objectProcessorFactory,
        SeedProcessorFactory $seedProcessorFactory,
        ContainerInterface $container = null,
        PluggableServiceInterface $pluggableService
    )
    {
        if (!($pluggableService instanceof EntityPluggableService))
            throw new InternalErrorException("Pluggable $class must be an instance of EntityPluggableService.");

        $this->registrationTag = $pluggableService->get("tag");
        $this->container = $container;
        $this->baseClass = $pluggableService->get("baseClass");
        $this->entities = $pluggableService->get("entity");

        if (!is_array($this->entities)) $this->entities = [$this->entities];

        $objectPluggableService = $this->createObjectPluggableService($pluggableService);
        $this->objectProcessor = $objectProcessorFactory->createProcessor($objectPluggableService);

        $seedPluggableService = $this->createSeedPluggableService($pluggableService);
        $this->seedProcessor = $seedProcessorFactory->createProcessor($seedPluggableService);
    }

    public function addPlugin($class, PluginInterface $pluginMeta)
    {
        $instance = $this->createInstance($class, $pluginMeta);
        $this->addSeedPlugin($instance, $pluginMeta);
        $this->addObjectPlugin($instance, $pluginMeta);
    }

    protected function getContainer()
    {
        return $this->container;
    }

    public function process()
    {
        $this->seedProcessor->process();
        $this->objectProcessor->process();
    }

    protected function checkInterface($instance)
    {
        if (!($instance instanceof EntityPluginInterface))
            throw new InternalErrorException("The $class plugin must implement the EntityPluginInterface.");
    }

    private function addSeedPlugin($instance, $pluginMeta)
    {
        $instance->loadSeedData();

        foreach ($this->entities as $entityName)
        {
            $entries = [];

            while ($entry = $instance->nextSeedEntry($entityName))
                $entries[] = $entry;

            if (count($entries))
            {
                $seedPlugin = new SimpleSeedPlugin($entries, $pluginMeta->get("update"));
                $seedPluginMeta = new SeedPlugin(["entity" => $entityName]);
                $this->seedProcessor->addPlugin($seedPlugin, $seedPluginMeta);
            }
        }
    }

    private function addObjectPlugin($instance, $pluginMeta)
    {
        $objectPluginMeta = new ObjectPlugin([
            "tag" => $pluginMeta->get("tag"),
            "id" => $instance->getId(),
            "depends" => $pluginMeta->get("depends")
        ]);

        $this->objectProcessor->addPlugin(get_class($instance), $objectPluginMeta);
    }

    private function createObjectPluggableService($pluggableService)
    {
        $objectPluggableService = new ObjectPluggableService();
        $objectPluggableService->set("tag", $pluggableService->getTag());
        $objectPluggableService->set("baseClass", $pluggableService->get("baseClass"));
        return $objectPluggableService;
    }

    private function createSeedPluggableService($pluggableService)
    {
        return new SeedPluggableService();
    }
}
