<?php
/**
 * @package    agitation/common
 * @link       http://github.com/agitation/AgitCommonBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CommonBundle\Annotation;

use Agit\CommonBundle\Exception\InternalErrorException;

trait SerializableAnnotationTrait
{
    use AnnotationTrait;

    public function serialize()
    {
        return serialize($this->getOptions());
    }

    public function unserialize($options)
    {
        return $this->setOptions(unserialize($options));
    }
}
