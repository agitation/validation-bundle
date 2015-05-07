<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Service;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractContainerAwareService extends ContainerAware
{
    // following our convention: Instances of named objects start with capital letters.
    protected $Container;

    public function __construct(ContainerInterface $Container)
    {
        $this->setContainer($Container);
        $this->Container = $Container;
    }
}