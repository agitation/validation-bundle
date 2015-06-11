<?php
/**
 * @package    agitation/intl
 * @link       http://github.com/agitation/AgitIntlBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\IntlBundle\EventListener;

abstract class AbstractTemporaryFilesListener
{
    private $relCachePathPrefix = "agit.intl.temp";

    // $bundleId: it is advised to pass a bundle id if a listener creates many
    // different files that could easily collide with files from other bundles.
    // May be the bundle alias or some other key fit to serve as a subdirectory name.
    protected function getCachePath($bundleId = null)
    {
        $path = sprintf("%s/%s.%s", sys_get_temp_dir(), $this->relCachePathPrefix, $this->getRelCachePath(), $bundleId);

        if (is_string($bundleId) && strlen($bundleId))
            $path .= "/$bundleId";

        return $path;
    }

    private function getRelCachePath()
    {
        return md5(get_class($this));
    }
}
