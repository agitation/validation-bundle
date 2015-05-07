<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Helper;

class StringHelper
{
    static public function getBareClassName($class)
    {
        if (is_object($class))
            $class = get_class($class);

        return substr(strrchr($class, "\\"), 1);
    }

    static public function createRandomString($length = 10)
    {
        $string = '';
        $letters = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $max = 61;

        for ($i=0; $i<$length; $i++)
            $string .= $letters[rand(0, $max)];

        return $string;
    }
}
