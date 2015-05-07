<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Object;

use Doctrine\Common\Cache\CacheProvider;
use Agit\CoreBundle\Exception\InternalErrorException;
use Agit\CoreBundle\Pluggable\Strategy\Cache\CacheRegistrationEvent;
use Agit\CoreBundle\Pluggable\Strategy\Cache\CacheProcessor;
use Agit\CoreBundle\Pluggable\Strategy\Cache\CacheData;

/**
 * Processes registered objects
 */
class ObjectProcessor extends CacheProcessor
{
    private $parentClass;

    public function __construct(CacheProvider $CacheProvider, $registrationTag, $parentClass)
    {
        parent::__construct($CacheProvider, $registrationTag);
        $this->parentClass = $parentClass;
    }

    public function register(CacheData $CacheData, $priority)
    {
        if (!is_string($CacheData->getData()) || !class_exists($CacheData->getData()))
            throw new InternalErrorException("Invalid plugin class.");

        $refl = new \ReflectionClass($CacheData->getData());

        if (!$refl->isSubclassOf($this->parentClass))
            throw new InternalErrorException("Plugin class must be a child of {$this->parentClass}.");

        parent::register($CacheData, $priority);
    }
}
