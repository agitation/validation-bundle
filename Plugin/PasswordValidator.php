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
 * @ObjectPlugin(tag="agit.validation", id="password")
 */
class PasswordValidator extends AbstractValidator
{
    private $allowedSpecialChars = '!§$%&/()[]{}=?@+*~#-_.:,;';

    public function validate($pass1, $pass2 = null)
    {
        if (strlen($pass1) < 8)
            throw new InvalidValueException(sprintf($this->translate->t("The password must have at least %d characters."), 8));

        if (!preg_match('|\d|', $pass1) || !preg_match('|[a-z]|i', $pass1))
            throw new InvalidValueException($this->translate->t("The password must contain at least one letter and one number."));

        if (preg_match('|[^a-z0-9'.preg_quote($this->allowedSpecialChars, '|').']|i', $pass1))
            throw new InvalidValueException(sprintf($this->translate->t("The password must only consist of letters, numbers and the characters %s."), $this->allowedSpecialChars));

        if (!is_null($pass2) && $pass1 !== $pass2)
            throw new InvalidValueException($this->translate->t("Both passwords must be identical."));
    }
}
