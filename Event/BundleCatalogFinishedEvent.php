<?php
/**
 * @package    agitation/intl
 * @link       http://github.com/agitation/AgitIntlBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\IntlBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Agit\IntlBundle\Service\TranslationCatalogService;

/**
 * This event is triggered after the files for generating a bundle catalog have
 * been collected and extracted. Listeners can use this hook to clean up
 * temporary files.
 *
 * This event does not offer any registration methods, as it is only used for
 * notification.
 */
class BundleCatalogFinishedEvent extends Event
{
    private $bundleAlias;

    public function __construct($bundleAlias)
    {
        $this->bundleAlias = $bundleAlias;
    }

    public function getBundleAlias()
    {
        return $this->bundleAlias;
    }
}
