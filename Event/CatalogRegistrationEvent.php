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
use Agit\IntlBundle\Service\GettextService;

/**
 * This event is triggered before the files for generating a global catalog are
 * collected and merged with the global catalog. Listeners can generate files in
 * a temporary location and pass them to the registerCatalogFile method.
 *
 * To remove temporary files, listen to the BundleFilesCleanupEvent.
 */
class CatalogRegistrationEvent extends Event
{
    private $translationCatalogService;

    private $gettextService;

    public function __construct(TranslationCatalogService $translationCatalogService, GettextService $gettextService)
    {
        $this->translationCatalogService = $translationCatalogService;
        $this->gettextService = $gettextService;
    }

    public function createCatalogHeader($locale)
    {
        return $this->gettextService->createCatalogHeader($locale);
    }
    public function registerCatalogFile($locale, $filePath)
    {
        return $this->translationCatalogService->registerCatalogFile($locale, $filePath);
    }
}
