<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Combined;

use Agit\CoreBundle\Service\ClassCollector;

/**
 * Creates object collector listeners.
 */
class CombinedPluginListenerFactory
{
    protected $classCollector;

    public function __construct(ClassCollector $classCollector)
    {
        $this->classCollector = $classCollector;
    }

    public function create($searchPath, $priority = 100)
    {
        return new CombinedPluginListener($this->classCollector, $searchPath, $priority);
    }
}
