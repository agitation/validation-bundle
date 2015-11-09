<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\Strategy;

use Agit\CommonBundle\Exception\InternalErrorException;

trait ServiceAwarePluginTrait
{
    private $serviceList = [];

    public function setService($name, $instance)
    {
        $this->serviceList[$name] = $instance;
    }

    protected function getService($name)
    {
        if (!isset($this->serviceList[$name]) || !is_object($this->serviceList[$name]))
            throw new InternalErrorException("Service `$name` was not injected.");

        return $this->serviceList[$name];
    }
}
