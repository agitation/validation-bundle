<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Seed;

use Agit\CoreBundle\Pluggable\PluggableServiceRegistrationEvent;


/**
 * Reusable listener for pluggable seeds.
 */
class SeedListener
{
    private $processorFactory;

    private $entityName;

    private $priority;

    private $removeObsolete;

    private $updateExisting;

    public function __construct(SeedProcessorFactory $processorFactory, $entityName, $priority, $removeObsolete, $updateExisting)
    {
        $this->processorFactory = $processorFactory;
        $this->entityName = $entityName;
        $this->priority = $priority;
        $this->removeObsolete = (bool)$removeObsolete;
        $this->updateExisting = (bool)$updateExisting;
    }

    public function onRegistration(PluggableServiceRegistrationEvent $registrationEvent)
    {
        $registrationEvent->registerProcessor(
            $this->processorFactory->create(
                $this->entityName,
                $this->priority,
                $this->removeObsolete,
                $this->updateExisting
        ));
    }
}
