<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Annotation;

use Agit\CoreBundle\Exception\InternalErrorException;

trait SerializableAnnotationTrait implements \Serializable
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
