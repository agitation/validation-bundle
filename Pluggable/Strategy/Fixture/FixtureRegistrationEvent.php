<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Fixture;

use Symfony\Component\EventDispatcher\Event;

/**
 * Data container for fixture objects.
 */
class FixtureRegistrationEvent extends Event
{
    public function __construct(FixtureProcessor $Processor)
    {
        $this->Processor = $Processor;
    }

    public function createContainer()
    {
        return new FixtureData();
    }

    public function register(FixtureData $FixtureData)
    {
        return $this->Processor->register($FixtureData);
    }
}