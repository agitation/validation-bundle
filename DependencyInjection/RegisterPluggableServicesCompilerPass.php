<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterPluggableServicesCompilerPass implements CompilerPassInterface
{
    private $containerBuilder;

    public function process(ContainerBuilder $containerBuilder)
    {
        $processor = $containerBuilder->findDefinition("agit.pluggable.processor");
        $services = $containerBuilder->findTaggedServiceIds("agit.pluggable");

        foreach ($services as $name => $tags) {
            foreach ($tags as $tag) {
                $processor->addMethodCall("addPluggableService", [$tag]);
            }
        }
    }
}
