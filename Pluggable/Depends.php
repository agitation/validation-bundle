<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable;

use Agit\BaseBundle\Annotation\AnnotationTrait;

/**
 * @Annotation
 */
class Depends
{
    use AnnotationTrait;

    // a list of services on which a plugin depends
    public $value = [];
}
