<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable\Seed;

use Agit\BaseBundle\Annotation\AnnotationTrait;
use Agit\BaseBundle\Pluggable\PluginInterface;

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
