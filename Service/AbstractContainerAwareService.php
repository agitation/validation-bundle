<?php
/**
 * @package    agitation/common
 * @link       http://github.com/agitation/AgitCommonBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CommonBundle\Service;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractContainerAwareService extends ContainerAware
{
    // following our convention: Instances of named objects start with capital letters.
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
        $this->container = $container;
    }
}