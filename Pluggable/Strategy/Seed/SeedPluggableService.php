<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\Strategy\Seed;

use Agit\BaseBundle\Annotation\AnnotationTrait;
use Agit\PluggableBundle\Strategy\PluggableServiceInterface;

class SeedPluggableService implements PluggableServiceInterface
{
    use AnnotationTrait;

    public function getType()
    {
        return "seed";
    }

    public function getTag()
    {
        return __NAMESPACE__ . '\SeedPlugin';
    }
}
