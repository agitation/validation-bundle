<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable\Cache;

use Agit\BaseBundle\Annotation\AnnotationTrait;
use Agit\BaseBundle\Pluggable\PluggableServiceInterface;

class CachePluggableService implements PluggableServiceInterface
{
    use AnnotationTrait;

    public function getType()
    {
        return "cache";
    }

    public function getTag()
    {
        return $this->tag;
    }

    // tag to which plugins shall register
    public $tag;

    // class which a plugin must inherit
    public $baseClass;
}
