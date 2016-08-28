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
use Agit\IntlBundle\Command\CreateCatalogsCommand;

/**
 * This event is triggered before the files for generating a global catalog are
 * collected and merged with the global catalog. Listeners can generate files in
 * a temporary location and pass them to the registerCatalogFile method.
 *
 * To remove temporary files, listen to the BundleFilesCleanupEvent.
 */
class CatalogRegistrationEvent extends Event
{
    private $processor;

    public function __construct(CreateCatalogsCommand $processor)
    {
        $this->processor = $processor;
    }

    public function registerCatalogFile($locale, $path)
    {
        return $this->processor->registerCatalogFile($locale, $path);
    }
}
