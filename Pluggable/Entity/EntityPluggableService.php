<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable\Entity;

use Agit\BaseBundle\Annotation\AnnotationTrait;
use Agit\BaseBundle\Pluggable\PluggableServiceInterface;

class EntityPluggableService implements PluggableServiceInterface
{
    use AnnotationTrait;

    public function getType()
    {
        return "entity";
    }

    public function getTag()
    {
        return $this->tag;
    }

    // tag to which plugins shall register
    public $tag;

    // class name from which plugins must inherit
    public $baseClass;

    // entity name where data is stored, string or array.
    public $entity;
}
