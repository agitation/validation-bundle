<?php
declare(strict_types=1);
/*
 * @package    agitation/validation-bundle
 * @link       http://github.com/agitation/validation-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Validator;

use Agit\IntlBundle\Tool\Translate;
use Agit\ValidationBundle\Exception\InvalidValueException;

class PasswordValidator extends AbstractValidator
{
    private $allowedSpecialChars = '!§$%&/()[]{}=?@+*~#-_.:,;';

    public function validate($pass1, $pass2 = null)
    {
        if (strlen($pass1) < 8)
        {
            throw new InvalidValueException(sprintf(Translate::t('The password must have at least %d characters.'), 8));
        }

        if (! preg_match('|\d|', $pass1) || ! preg_match('|[a-z]|i', $pass1))
        {
            throw new InvalidValueException(Translate::t('The password must contain at least one letter and one number.'));
        }

        if (preg_match('|[^a-z0-9' . preg_quote($this->allowedSpecialChars, '|') . ']|i', $pass1))
        {
            throw new InvalidValueException(sprintf(Translate::t('The password must only consist of letters, numbers and the characters %s.'), $this->allowedSpecialChars));
        }

        if ($pass2 !== null&& $pass1 !== $pass2)
        {
            throw new InvalidValueException(Translate::t('Both passwords must be identical.'));
        }
    }
}
