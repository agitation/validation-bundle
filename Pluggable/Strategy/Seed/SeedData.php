<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Seed;

use Agit\CoreBundle\Exception\InternalErrorException;
use Agit\CoreBundle\Pluggable\Strategy\Cache\CacheData;

/**
 * Data container for objects to be registered. Simply reusing the CacheData
 * container for now, as it has all we need.
 */
class SeedData extends CacheData
{
}