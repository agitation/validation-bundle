<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Combined;

use Symfony\Component\EventDispatcher\Event;

class CombinedRegistrationEvent extends Event
{
    public function __construct(CombinedProcessor $processor)
    {
        $this->processor = $processor;
    }

    public function createContainer()
    {
        return new CombinedData();
    }

    public function getParentClass()
    {
        return $this->processor->getParentClass();
    }

    public function getEntityNames()
    {
        return $this->processor->getEntityNames();
    }

    public function register(CombinedData $combinedData, $priority)
    {
        return $this->processor->register($combinedData, $priority);
    }
}