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
 * This event is triggered before the files for generating a bundle catalog are
 * collected and extracted. Listeners should set up the files for the bundle in
 * question, and pass them to the registerCatalogFiles method.
 *
 * To remove temporary files, listen to the CatalogFinishedEvent.
 */
class TranslationFilesRegistrationEvent extends Event
{
    private $bundleAlias;

    private $TranslationCatalogService;

    public function __construct(TranslationCatalogService $TranslationCatalogService, $bundleAlias)
    {
        $this->bundleAlias = $bundleAlias;
        $this->TranslationCatalogService = $TranslationCatalogService;
    }

    public function getBundleAlias()
    {
        return $this->bundleAlias;
    }

    public function registerCatalogFiles($progLang, array $fileList)
    {
        return $this->TranslationCatalogService->registerCatalogFiles($progLang, $fileList);
    }
}
