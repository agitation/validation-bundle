<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\Strategy\Entity;

use Agit\CoreBundle\Annotation\AnnotationTrait;
use Agit\PluggableBundle\Strategy\PluggableServiceInterface;

class EntityPluggableService implements PluggableServiceInterface
{
    use AnnotationTrait;

    public function getType()
    {
        return "entity";
    }

    // tag to which plugins shall register
    public $tag;

    // class name from which plugins must inherit
    public $baseClass;

    // entity name where data is stored, string or array.
    public $entity;
}
