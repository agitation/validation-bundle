<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Object;

use Symfony\Component\EventDispatcher\Event;

class ObjectRegistrationEvent extends Event
{
    public function __construct(ObjectProcessor $Processor)
    {
        $this->Processor = $Processor;
    }

    public function createContainer()
    {
        return new ObjectData();
    }

    public function getParentClass()
    {
        return $this->Processor->getParentClass();
    }

    public function getEntityNames()
    {
        return $this->Processor->getEntityNames();
    }

    public function register(ObjectData $ObjectData, $priority)
    {
        return $this->Processor->registerObject($ObjectData, $priority);
    }
}
