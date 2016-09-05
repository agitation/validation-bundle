<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class RegisterProcessorsCompilerPass implements CompilerPassInterface
{
    private $containerBuilder;

    public function process(ContainerBuilder $containerBuilder)
    {
        $processor = $containerBuilder->findDefinition('agit.pluggable.processor');
        $services = $containerBuilder->findTaggedServiceIds("agit.pluggable.processor_factory");

        foreach ($services as $name => $tags)
            foreach ($tags as $tag)
                if (isset($tag['type']))
                    $processor->addMethodCall('addProcessorFactory', [$tag['type'], new Reference($name)]);
    }
}
