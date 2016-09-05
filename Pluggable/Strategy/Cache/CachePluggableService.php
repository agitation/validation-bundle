<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\Strategy\Cache;

use Agit\BaseBundle\Annotation\AnnotationTrait;
use Agit\PluggableBundle\Strategy\PluggableServiceInterface;

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
