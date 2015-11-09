<?php
/**
 * @package    agitation/common
 * @link       http://github.com/agitation/AgitCommonBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CommonBundle\Helper;

class AnnotationHelper
{
    static public function cleanDocComment($comment)
    {
        // start/end of comment block
        $comment = preg_replace('|^/.*$|m', '', $comment);
        $comment = preg_replace('|^[[:blank:]]*\*/[[:blank:]]*$|m', '', $comment);

        // remove annotation lines
        $comment = preg_replace('|^[[:blank:]]*\*[[:blank:]]*@[a-z].*$|im', '', $comment);

        // remove asterisks and whitespace on line beginning
        $comment = preg_replace('|^[[:blank:]]*\*[[:blank:]]*|m', '', $comment);

        $comment = trim($comment);

        return $comment;
    }
}