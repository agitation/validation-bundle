<?php
/**
 * @package    agitation/intl
 * @link       http://github.com/agitation/AgitIntlBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\IntlBundle;

class Translate
{
    static public function t($string)
    {
        return gettext($string);
    }

    static public function n($string1, $string2, $num)
    {
        return ngettext($string1, $string2, $num);
    }

    static public function x($string, $context)
    {
        // based on http://www.php.net/manual/de/book.gettext.php#89975
        $contextString = "{$context}\004{$string}";
        $translation = self::t($contextString);
        return ($translation === $contextString) ? $string : $translation;
    }

    static public function u($string, $locale = null)
    {
        if (!$locale) $locale = setlocale(LC_MESSAGES, 0);

        $lang = substr($locale, 0, 2);
        $obj = self::multilangStringToObject($string);

        if (isset($obj->$lang))
            $newString = $obj->$lang;
        elseif (isset($obj->en))
            $newString = $obj->en;
        else
            $newString = $string;

        return $newString;
    }

    /**
     * This method is just a helper to ensure that strings are caught by xgettext.
     * The string itself will usually be translated in a different context.
     */
    static public function noop($string)
    {
        return $string;
    }

    /**
     * Same as noop(), only for strings with plural forms
     */
    static public function noopN($string1, $string2, $num)
    {
        return $string1;
    }

    /**
     * Same as noop(), only for strings with context
     */
    static public function noopX($string, $context)
    {
        return $string;
    }

    static public function multilangStringToObject($string)
    {
        $obj = new \stdClass;

        if (strpos($string, '[:') !== false && preg_match('|^\[:[a-z]{2}\]|', $string))
        {
            $stringarray = preg_split('|\[:([a-z]{2})\]|', $string, -1, PREG_SPLIT_DELIM_CAPTURE);

            // throw away (empty) first element and renumber.
            // NOTE: we can't use PREG_SPLIT_NO_EMPTY above, because it would break empty translations.
            array_shift($stringarray);
            $stringarray = array_values($stringarray);

            if (is_array($stringarray) && count($stringarray) >= 2)
                foreach ($stringarray as $k=>$v)
                    if (!($k%2) && $v && isset($stringarray[$k+1]))
                        $obj->$v = $stringarray[$k+1];


        }

        return $obj;
    }

    static public function multilangObjectToString(\stdClass $object)
    {
        $string = '';

        foreach((array)$object as $lang=>$text)
            $string .= "[:$lang]$text";

        return $string;
    }
}
