<?php
/**
 * @package    agitation/intl
 * @link       http://github.com/agitation/AgitIntlBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Service;

/**
 * May be implemented by a bundle to provide a UI-configured services (e.g.
 * through the agitation/settings bundle). The service must have the tag
 * `agit.intl.localeconfig` and implement this interface.
 *
 * NB: The *active* locales are not the *available* locales, and the *primary* locale
 * is not the *default* locale – i.e. we cannot use the Intl/LocaleService here.
 */
interface LocaleConfigInterface
{
    public function getActiveLocales();

    public function getPrimaryLocale();
}
