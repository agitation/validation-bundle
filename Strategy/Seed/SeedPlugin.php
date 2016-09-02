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
use Agit\PluggableBundle\Strategy\PluginInterface;

/**
 * @Annotation
 */
class SeedPlugin implements PluginInterface
{
    use AnnotationTrait;

    // the entity name for the database entries provided by this seed plugin
    public $entity;

    // implement the ServiceAwareInterface to get dependencies injected
    public $depends = [];
}
