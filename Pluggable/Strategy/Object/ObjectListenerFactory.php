<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Object;

/**
 * Creates object collector listeners.
 */
class ObjectListenerFactory
{
    protected $fileLocator;

    public function __construct(ObjectProcessorFactory $processorFactory)
    {
        $this->processorFactory = $processorFactory;
    }

    public function create($registrationTag, $parentClass)
    {
        return new ObjectListener($this->processorFactory, $registrationTag, $parentClass);
    }
}
