<?php
/**
 * @package    agitation/intl
 * @link       http://github.com/agitation/AgitIntlBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Agit\BaseBundle\Command\TranslationCatalogCommand;

/**
 * This event is triggered before the files for generating a global catalog are
 * collected and merged with the global catalog. Listeners can generate files
 * under getCacheBasePath and pass them to the registerCatalogFile method.
 */
class TranslationCatalogEvent extends Event
{
    private $processor;

    public function __construct(TranslationCatalogCommand $processor, $cacheBasePath)
    {
        $this->processor = $processor;
        $this->cacheBasePath = $cacheBasePath;
    }

    public function getCacheBasePath()
    {
        return $this->cacheBasePath;
    }

    public function registerCatalogFile($locale, $path)
    {
        return $this->processor->registerCatalogFile($locale, $path);
    }
}
