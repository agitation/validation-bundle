<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable;

/**
 * NOTE: You can use the ServiceAwarePluginTrait as an instant implementation.
 * But you still have to declare that your class implements this interface.
 */
interface ServiceAwarePluginInterface
{
    public function setService($name, $instance);
}
