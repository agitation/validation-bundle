<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Cache;

use Agit\CoreBundle\Exception\InternalErrorException;
use Doctrine\Common\Cache\CacheProvider;
use Agit\CoreBundle\Pluggable\Strategy\ProcessorInterface;

/**
 * Processes registered objects
 */
class CacheProcessor implements ProcessorInterface
{
    // caching implementation
    private $CacheProvider;

    // the list of available plugins to services
    private $plugins = [];

    protected $registrationTag;

    public function __construct(CacheProvider $CacheProvider, $registrationTag)
    {
        $this->CacheProvider = $CacheProvider;
        $this->registrationTag = $registrationTag;
    }

    public function getRegistrationTag()
    {
        return $this->registrationTag;
    }

    public function createRegistrationEvent()
    {
        return new CacheRegistrationEvent($this);
    }

    public function register(CacheData $CacheData, $priority)
    {
        $this->plugins[$CacheData->getId()] = $CacheData->getData();
    }

    public function process()
    {
        $this->getCacheProvider()->save($this->getRegistrationTag(), $this->plugins);
    }

    public function getPriority()
    {
        return 1;
    }

    protected function getCacheProvider()
    {
        return $this->CacheProvider;
    }
}
