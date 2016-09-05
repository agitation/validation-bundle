<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\Strategy\Cache;

interface CachePluginInterface
{
    /**
     * This method is expected to collect all plugin entries into a stack and
     * keep them available for later retrieval with the `nextCacheEntry` method.
     */
    public function load();

    /**
     * This method is expected to return one CacheEntry object after another, or
     * `null` if the stack is empty.
     */
    public function nextCacheEntry();
}
