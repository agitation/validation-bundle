<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Agit\PluggableBundle\DependencyInjection\RegisterProcessorsCompilerPass;
use Agit\PluggableBundle\DependencyInjection\RegisterPluggableServicesCompilerPass;

class AgitPluggableBundle extends Bundle
{
    public function build(ContainerBuilder $containerBuilder)
    {
        parent::build($containerBuilder);

        $containerBuilder->addCompilerPass(new RegisterProcessorsCompilerPass());

        $containerBuilder->addCompilerPass(
            new RegisterPluggableServicesCompilerPass(),
            PassConfig::TYPE_AFTER_REMOVING
        );
    }
}
