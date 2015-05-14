<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Combined;

interface CombinedPluginInterface
{
    // returns an identifier string
    public static function getPluginId();

    // returns an array of data rows
    public static function getFixtures($entityName);
}
