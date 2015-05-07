<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Fixture;

/**
 * Creates object collector listeners.
 */
class FixtureListenerFactory
{
    protected $FileLocator;

    public function __construct(FixtureProcessorFactory $ProcessorFactory)
    {
        $this->ProcessorFactory = $ProcessorFactory;
    }

    public function create($entityName, $priority, $removeObsolete = false, $updateExisting = true)
    {
        return new FixtureListener($this->ProcessorFactory, $entityName, $priority, $removeObsolete, $updateExisting);
    }
}
