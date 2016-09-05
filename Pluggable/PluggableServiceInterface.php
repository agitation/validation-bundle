<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable;

interface PluggableServiceInterface
{
    // must return a strategy identifier
    public function getType();

    // some strategies require plugins to be tagged. If the strategy is tagless,
    // this method should return the plugin annotation class instead.
    public function getTag();
}
