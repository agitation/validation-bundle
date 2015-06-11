<?php
/**
 * @package    agitation/intl
 * @link       http://github.com/agitation/AgitIntlBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\IntlBundle\EventListener;

use Agit\IntlBundle\Event\BundleFilesCleanupEvent;
use Symfony\Component\Filesystem\Filesystem;

// reusable listener for cleaning up a temporary storage path
class BundleFilesCleanupListener extends AbstractTemporaryFilesListener
{
    private $Filesystem;

    public function __construct(Filesystem $Filesystem)
    {
        $this->Filesystem = $Filesystem;
    }

    public function onRegistration(BundleFilesCleanupEvent $RegistrationEvent)
    {
        $cachePath = $this->getCachePath($RegistrationEvent->getBundleAlias());

        if ($this->Filesystem->exists($cachePath))
            $this->Filesystem->remove($cachePath);
    }
}
