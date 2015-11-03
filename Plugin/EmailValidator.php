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

/**
 * @ObjectPlugin(tag="agit.validation", id="email")
 */
class EmailValidator extends AbstractValidator
{
    public function validate($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL))
            throw new InvalidValueException($this->translate->t("The e-mail address is malformed."));

        // although technically valid, we don't accept e-mail adresses with capital letters
        if (strtolower($value) !== $value)
            throw new InvalidValueException($this->translate->t("The e-mail address must not contain capital letters."));
    }
}
