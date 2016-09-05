<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable\Object;

use Agit\BaseBundle\Annotation\AnnotationTrait;
use Agit\BaseBundle\Pluggable\PluggableServiceInterface;

class ObjectPluggableService implements PluggableServiceInterface
{
    use AnnotationTrait;

    public function getType()
    {
        return "object";
    }

    public function getTag()
    {
        return $this->tag;
    }

    // tag to which plugins shall register
    public $tag;

    // class name from which plugins must inherit
    public $baseClass;
}
