<?php
declare(strict_types=1);

/*
 * @package    agitation/validation-bundle
 * @link       http://github.com/agitation/validation-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle;

use Agit\ValidationBundle\DependencyInjection\RegisterValidatorsCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AgitValidationBundle extends Bundle
{
    public function build(ContainerBuilder $containerBuilder)
    {
        parent::build($containerBuilder);
        $containerBuilder->addCompilerPass(new RegisterValidatorsCompilerPass());
    }
}
