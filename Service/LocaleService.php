<?php
/**
 * @package    agitation/intl
 * @link       http://github.com/agitation/AgitIntlBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\IntlBundle\Service;

use Agit\CoreBundle\Exception\InternalErrorException;

class LocaleService
{
    private $defaultLocale = 'en_GB';

    private $currentLocale;

    private $translationsPath;

    private $availableLocales;

    private $LocaleConfigService;

    public function __construct(LocaleConfigInterface $LocaleConfigService = null, $availableLocales, $translationsPath, $textdomain)
    {
        $this->availableLocales = $availableLocales;

        bindtextdomain($textdomain, $translationsPath);
        textdomain($textdomain);

        $this->setLocale($this->defaultLocale);
        $this->LocaleConfigService = $LocaleConfigService;
    }

    // default locale for this distribution
    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    // available locales for this distribution
    public function getAvailableLocales()
    {
        return $this->availableLocales;
    }

    // default locale for this installation
    public function getPrimaryLocale()
    {
        return $this->LocaleConfigService
            ? $this->LocaleConfigService->getPrimaryLocale()
            : $this->defaultLocale;
    }

    // available locales for this installation
    public function getActiveLocales()
    {
        return $this->LocaleConfigService
            ? $this->LocaleConfigService->getActiveLocales()
            : $this->availableLocales;
    }

    public function setLocale($locale)
    {
        if (!in_array($locale, $this->availableLocales))
            throw new InternalErrorException("The locale '$locale' is not available.");

        putenv("LANGUAGE=$locale.UTF-8"); // for CLI
        setlocale(LC_ALL, "$locale.utf8");
        setlocale(LC_NUMERIC, "en_GB.utf8"); // avoid strange results with floats in sprintf

        $this->currentLocale = $locale;
    }

    public function getLocale()
    {
        return $this->currentLocale;
    }

    public function getUserLocale()
    {
        $browserLocale = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']) : '';

        $userLocale = (in_array($browserLocale, $this->availableLocales))
            ? $browserLocale
            : '';

        // try locales with same language but different country
        if (!$userLocale)
        {
            foreach ($this->availableLocales as $locale)
            {
                if (strtolower(substr($locale, 0, 2)) == strtolower(substr($browserLocale, 0, 2)))
                {
                    $userLocale = $locale;
                    break;
                }
            }
        }

        return $userLocale ?: reset($this->availableLocales);
    }


}
