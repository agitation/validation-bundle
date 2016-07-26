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
    // used internally to store the currently set locale
    static private $locale = "en_GB";

    static private $appLocale = "en_GB";

    static public function getLocale()
    {
        return self::$locale;
    }

    static public function getAppLocale()
    {
        return self::$appLocale;
    }

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

    // like t(), only in the appLocale. Useful for logging etc.
    static public function tl($string)
    {
        $locale = self::$locale;
        self::_setLocale(self::$appLocale);
        $translation = self::t($string);
        self::_setLocale($locale);
        return $translation;
    }

    // like n(), only in the appLocale. Useful for logging etc.
    static public function nl($string1, $string2, $num)
    {
        $locale = self::$locale;
        self::_setLocale(self::$appLocale);
        $translation = self::n($string1, $string2, $num);
        self::_setLocale($locale);
        return $translation;
    }

    // like x(), only in the appLocale. Useful for logging etc.
    static public function xl($string, $context)
    {
        $locale = self::$locale;
        self::_setLocale(self::$appLocale);
        $translation = self::x($string, $context);
        self::_setLocale($locale);
        return $translation;
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

    /**
     * DO NOT CALL THIS METHOD; use LocaleService->setLocale instead.
     * This method is only public because both Translate and LocaleService
     * need its functionality and we want to avoid duplicate code.
     */
    static public function _setLocale($locale)
    {
        putenv("LANGUAGE=$locale.UTF-8"); // for CLI
        setlocale(LC_ALL, "$locale.utf8");
        setlocale(LC_NUMERIC, "en_GB.utf8"); // avoid strange results with floats in sprintf
        self::$locale = $locale;
    }

    /**
     * DO NOT CALL THIS METHOD; it is only public for LocaleService.
     */
    static public function _setAppLocale($locale)
    {
        self::$appLocale = $locale;
    }
}
