<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\Strategy;

interface PluggableServiceInterface
{
    // must return a strategy identifier
    public function getType();

    // some strategies require plugins to be tagged. If the strategy is tagless,
    // this method should return the plugin annotation class instead.
    public function getTag();
}
