<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\Strategy\Entity;

use Doctrine\Common\Cache\CacheProvider;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EntityLoaderFactory
{
    private $entityManager;

    private $cacheProvider;

    private $container;

    public function __construct(CacheProvider $cacheProvider, EntityManager $entityManager, ContainerInterface $container = null)
    {
        $this->cacheProvider = $cacheProvider;
        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    public function create($registrationTag, $entityName)
    {
        return new EntityLoader($this->cacheProvider, $this->entityManager, $this->container, $registrationTag, $entityName);
    }
}
