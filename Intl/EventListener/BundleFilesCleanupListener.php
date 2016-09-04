<?php
/**
 * @package    agitation/intl
 * @link       http://github.com/agitation/AgitIntlBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\IntlBundle\EventListener;

use Agit\IntlBundle\Event\CleanupEvent;
use Symfony\Component\Filesystem\Filesystem;

// reusable listener for cleaning up a temporary storage path
class BundleFilesCleanupListener extends AbstractTemporaryFilesListener
{
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function onRegistration(CleanupEvent $registrationEvent)
    {
        $cachePath = $this->getCachePath($registrationEvent->getBundleAlias());

        if ($this->filesystem->exists($cachePath))
            $this->filesystem->remove($cachePath);
    }
}
