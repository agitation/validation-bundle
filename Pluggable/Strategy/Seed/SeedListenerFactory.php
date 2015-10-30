<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Seed;

/**
 * Creates object collector listeners.
 */
class SeedListenerFactory
{
    protected $fileLocator;

    public function __construct(SeedProcessorFactory $processorFactory)
    {
        $this->processorFactory = $processorFactory;
    }

    public function create($entityName, $priority, $removeObsolete = false, $updateExisting = true)
    {
        return new SeedListener($this->processorFactory, $entityName, $priority, $removeObsolete, $updateExisting);
    }
}
