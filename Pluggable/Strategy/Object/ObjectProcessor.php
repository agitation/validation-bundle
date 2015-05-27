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

    public function createRegistrationEvent()
    {
        return new ObjectRegistrationEvent($this);
    }

    public function getParentClass()
    {
        return $this->parentClass;
    }

    public function register(CacheData $CacheData, $priority)
    {
        throw new InternalErrorException("This method is not available on this processor. Use registerObject() instead.");
    }

    public function registerObject(ObjectData $ObjectData, $priority)
    {
        if (!is_string($ObjectData->getClass()) || !class_exists($ObjectData->getClass()))
            throw new InternalErrorException("Invalid plugin class.");

        $refl = new \ReflectionClass($ObjectData->getClass());

        if (!$refl->isSubclassOf($this->parentClass))
            throw new InternalErrorException(sprintf("%s must be a subclass of %s.", $ObjectData->getClass(), $this->parentClass));

        $this->addPlugin($ObjectData->getId(), $ObjectData->getClass());
    }
}
