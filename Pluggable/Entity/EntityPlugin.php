<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable\Entity;

use Agit\BaseBundle\Annotation\AnnotationTrait;
use Agit\BaseBundle\Pluggable\PluginInterface;

/**
 * @Annotation
 */
class EntityPlugin implements PluginInterface
{
    use AnnotationTrait;

    // the tag to which the plugin shall register
    public $tag;

    // whether or not existing database entries should be udpated
    public $update = true;

    // the plugin can use the ServiceAwareTrait to get dependencies injected
    public $depends = [];
}
