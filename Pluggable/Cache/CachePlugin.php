<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable\Cache;

use Agit\BaseBundle\Annotation\AnnotationTrait;
use Agit\BaseBundle\Pluggable\PluginInterface;

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
