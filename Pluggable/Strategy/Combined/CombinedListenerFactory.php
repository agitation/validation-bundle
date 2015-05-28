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
use Agit\CoreBundle\Pluggable\Strategy\Seed\SeedProcessorFactory;

/**
 * Creates object collector listeners.
 */
class CombinedListenerFactory
{
    private $ObjectProcessorFactory;

    private $SeedProcessorFactory;

    public function __construct(ObjectProcessorFactory $ObjectProcessorFactory, SeedProcessorFactory $SeedProcessorFactory)
    {
        $this->ObjectProcessorFactory = $ObjectProcessorFactory;
        $this->SeedProcessorFactory = $SeedProcessorFactory;
    }

    public function create($registrationTag, $parentClass, array $entityNameList, $seedDeleteObsolete = true, $seedUpdateExisting = true)
    {
        return new CombinedListener(
            $this->ObjectProcessorFactory,
            $this->SeedProcessorFactory,
            $registrationTag,
            $parentClass,
            $entityNameList,
            $seedDeleteObsolete,
            $seedUpdateExisting);
    }
}
