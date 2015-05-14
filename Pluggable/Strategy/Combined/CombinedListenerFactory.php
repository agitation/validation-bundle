<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Combined;

use Agit\CoreBundle\Pluggable\Strategy\Object\ObjectProcessorFactory;
use Agit\CoreBundle\Pluggable\Strategy\Fixture\FixtureProcessorFactory;

/**
 * Creates object collector listeners.
 */
class CombinedListenerFactory
{
    private $ObjectProcessorFactory;

    private $FixtureProcessorFactory;

    public function __construct(ObjectProcessorFactory $ObjectProcessorFactory, FixtureProcessorFactory $FixtureProcessorFactory)
    {
        $this->ObjectProcessorFactory = $ObjectProcessorFactory;
        $this->FixtureProcessorFactory = $FixtureProcessorFactory;
    }

    public function create($registrationTag, $parentClass, array $entityNameList, $fixtureDeleteObsolete = true, $fixtureUpdateExisting = true)
    {
        return new CombinedListener(
            $this->ObjectProcessorFactory,
            $this->FixtureProcessorFactory,
            $registrationTag,
            $parentClass,
            $entityNameList,
            $fixtureDeleteObsolete,
            $fixtureUpdateExisting);
    }
}
