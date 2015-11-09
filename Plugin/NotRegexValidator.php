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
 * @ObjectPlugin(tag="agit.validation", id="notRegex")
 */
class NotRegexValidator extends AbstractValidator
{
    public function validate($value, $regex = '|*|')
    {
        if (preg_match($regex, $value))
            throw new InvalidValueException(Translate::t("The value doesn’t match the required pattern."));
    }
}
