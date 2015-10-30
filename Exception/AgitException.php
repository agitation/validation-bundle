<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Exception;

use Agit\CoreBundle\Helper\AnnotationHelper;

/**
 * The mother of all Agit exceptions.
 */
abstract class AgitException extends \Exception
{
    public function getErrorCode()
    {
        $code = str_replace(['Bundle', 'Exception', '\\'], ' ', get_class($this));
        $code = preg_replace('|\s+|', ':', trim($code));

        return $code;
    }

    public function getErrorDescription()
    {
        $reflObject = new \ReflectionObject($this);
        $comment = AnnotationHelper::cleanDocComment($reflObject->getDocComment()) ?: '';

        return $comment;
    }
}
