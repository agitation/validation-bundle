<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterValidatorsCompilerPass implements CompilerPassInterface
{
    private $containerBuilder;

    public function process(ContainerBuilder $containerBuilder)
    {
        $processor = $containerBuilder->findDefinition("agit.validation");
        $services = $containerBuilder->findTaggedServiceIds("agit.validator");

        foreach ($services as $name => $tags) {
            foreach ($tags as $tag) {
                $processor->addMethodCall("addValidator", [$tag["id"], new Reference($name)]);
            }
        }
    }
}
