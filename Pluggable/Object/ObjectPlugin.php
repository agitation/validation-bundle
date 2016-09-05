<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable\Object;

use Agit\BaseBundle\Annotation\AnnotationTrait;
use Agit\BaseBundle\Pluggable\PluginInterface;

/**
 * @Annotation
 */
class ObjectPlugin implements PluginInterface
{
    use AnnotationTrait;

    // the tag to which the plugin shall register
    public $tag;

    // the id to identify the plugin within its group
    public $id;

    // the plugin can use the ServiceAwareTrait to get dependencies injected
    public $depends = [];
}
