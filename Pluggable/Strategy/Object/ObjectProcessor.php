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

    public function __construct(CacheProvider $cacheProvider, $registrationTag, $parentClass)
    {
        parent::__construct($cacheProvider, $registrationTag);
        $this->parentClass = $parentClass;
    }

    public function createRegistrationEvent()
    {
        return new ObjectRegistrationEvent($this);
    }

    public function getParentClass()
    {
        return $this->parentClass;
    }

    public function register(CacheData $cacheData, $priority)
    {
        throw new InternalErrorException("This method is not available on this processor. Use registerObject() instead.");
    }

    public function registerObject(ObjectData $objectData, $priority)
    {
        if (!is_string($objectData->getClass()) || !class_exists($objectData->getClass()))
            throw new InternalErrorException("Invalid plugin class.");

        $refl = new \ReflectionClass($objectData->getClass());

        if (!$refl->isSubclassOf($this->parentClass))
            throw new InternalErrorException(sprintf("%s must be a subclass of %s.", $objectData->getClass(), $this->parentClass));

        $this->addPlugin($objectData->getId(), $objectData->getClass());
    }
}
