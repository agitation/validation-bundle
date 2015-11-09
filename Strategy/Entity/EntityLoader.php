<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\Strategy\Entity;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Cache\CacheProvider;
use Agit\PluggableBundle\Strategy\Object\ObjectLoader;
use Agit\CommonBundle\Exception\InternalErrorException;
use Agit\PluggableBundle\Strategy\ServiceAwarePluginInterface;

class EntityLoader extends ObjectLoader
{
    private $registrationTag;

    private $entityName;

    private $entityManager;

    private $cacheProvider;

    private $container;

    public function __construct(CacheProvider $cacheProvider, EntityManager $entityManager, ContainerInterface $container = null, $registrationTag, $entityName)
    {
        parent::__construct($cacheProvider, $container, $registrationTag);

        $this->cacheProvider = $cacheProvider;
        $this->entityManager = $entityManager;
        $this->container = $container;
        $this->registrationTag = $registrationTag;
        $this->entityName = $entityName;
    }

    protected function loadObject($id)
    {
        parent::loadObject($id);

        $entity = $this->entityManager->find($this->entityName, $id);
        $this->objectList[$id]['instance']->setEntity($entity);
    }
}
