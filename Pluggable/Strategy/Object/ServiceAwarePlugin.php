<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Object;

use Agit\CoreBundle\Exception\InternalErrorException;

trait ServiceAwarePlugin
{
    private $serviceList = [];

    abstract public function getServiceDependencies();

    public function setService($name, $instance)
    {
        $this->serviceList[$name] = $instance;
    }

    protected function getService($name)
    {
        if (!isset($this->serviceList[$name]))
            throw new InternalErrorException("Service '$name' was not injected.");

        return $this->serviceList[$name];
    }
}
