<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

// namespace Agit\CoreBundle\Pluggable\Strategy\Combined;
//
// use Agit\CoreBundle\Exception\InternalErrorException;
// use Doctrine\Common\Cache\CacheProvider;
//
// /**
//  * Creates an combined processor instance.
//  */
// class CombinedProcessorFactory
// {
//     public function __construct(ObjectProcessorFactory $objectProcessorFactory, SeedProcessorFactory $seedProcessorFactory)
//     {
//         $this->objectProcessorFactory = $objectProcessorFactory;
//         $this->seedProcessorFactory = $seedProcessorFactory;
//     }
//
//     /**
//      * @param string $parentClass name of the class/interface which plugin objects should inherit/implement.
//      */
//     public function create($registrationTag, $parentClass, array $entityNameList, $seedDeleteObsolete, $seedUpdateExisting)
//     {
//         return new CombinedProcessor($this->cacheProvider, $registrationTag, $parentClass);
//     }
// }
