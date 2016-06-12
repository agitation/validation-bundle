<?php
/**
 * @package    agitation/validation
 * @link       http://github.com/agitation/AgitValidationBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Plugin;

use Agit\ValidationBundle\Exception\InvalidValueException;
use Agit\PluggableBundle\Strategy\Object\ObjectPlugin;
use Agit\IntlBundle\Translate;

/**
 * @ObjectPlugin(tag="agit.validation", id="url")
 */
class UrlValidator extends AbstractValidator
{
    public function validate($value, $strict = true)
    {
        if (!filter_var($value, FILTER_VALIDATE_URL) || strpos($value, 'http') !== 0)
            throw new InvalidValueException(Translate::t("The URL is invalid."));

        $urlParts = parse_url($value);

        if (!$urlParts || !isset($urlParts['scheme']) || !isset($urlParts['host']) || !in_array($urlParts['scheme'], array('http', 'https')) || !preg_match("#^(([a-z]|[a-z][a-z0-9\-]*[a-z0-9])\.)*([a-z]|[a-z][a-z0-9\-]*[a-z0-9])$#i", $urlParts['host']))
            throw new InvalidValueException(Translate::t("The URL is invalid."));

        if ($strict && (isset($urlParts['user']) || isset($urlParts['pass']) || isset($urlParts['port'])))
            throw new InvalidValueException(Translate::t("The URL is malformed."));
    }
}
