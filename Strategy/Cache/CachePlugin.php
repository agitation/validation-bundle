<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\Strategy\Cache;

use Agit\BaseBundle\Annotation\AnnotationTrait;
use Agit\PluggableBundle\Strategy\PluginInterface;

/**
 * @Annotation
 */
class CachePlugin implements PluginInterface
{
    use AnnotationTrait;

    // the tag to which the plugin shall register
    public $tag;

    // the id to identify the plugin among other plugins with this tag
    public $id;

    // the plugin can use the ServiceAwareTrait to get dependencies injected
    public $depends = [];
}
