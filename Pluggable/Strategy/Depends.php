<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\Strategy;

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
