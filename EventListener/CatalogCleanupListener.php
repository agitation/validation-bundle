<?php
/**
 * @package    agitation/intl
 * @link       http://github.com/agitation/AgitIntlBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\IntlBundle\EventListener;

use Agit\IntlBundle\Event\CatalogCleanupEvent;
use Symfony\Component\Filesystem\Filesystem;

// NOTE: Other than BundleFileCleanupListener, this listener does NOT have to be
// used by each bundle creating files with the CatalogRegistrationListener: This
// CatalogCleanupListener needs to run only once, and is registered by
// AgitIntlBundle already.
//
// However, if a bundle writes catalogs to a different location than
// `$this->getCachePath()`, then of course it has to clean up after itself.
class CatalogCleanupListener extends AbstractTemporaryFilesListener
{
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function onRegistration(CatalogCleanupEvent $registrationEvent)
    {
        $cachePath = $this->getCachePath();

        if ($this->filesystem->exists($cachePath))
            $this->filesystem->remove($cachePath);
    }
}
