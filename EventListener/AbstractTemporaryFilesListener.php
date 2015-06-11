<?php
/**
 * @package    agitation/intl
 * @link       http://github.com/agitation/AgitIntlBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\IntlBundle\EventListener;

use Agit\IntlBundle\Event\BundleFilesRegistrationEvent;
use Agit\CoreBundle\Service\FileCollector;

abstract class AbstractTemporaryFilesListener
{
    private $relCachePathPrefix = "agit.intl.temp";

    protected function getCachePath($bundleAlias)
    {
        return sprintf("%s/%s.%s/%s", sys_get_temp_dir(), $this->relCachePathPrefix, $this->getRelCachePath(), $bundleAlias);
    }

    private function getRelCachePath()
    {
        return md5(get_class($this));
    }
}
