<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\Strategy;

/**
 * NOTE: You can use the ServiceAwarePluginTrait as an instant implementation.
 * But you still have to declare that your class implements this interface.
 */
interface ServiceAwarePluginInterface
{
    public function setService($name, $instance);
}
