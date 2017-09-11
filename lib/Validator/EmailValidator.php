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

class EmailValidator extends AbstractValidator
{
    public function validate($value)
    {
        if (! filter_var($value, FILTER_VALIDATE_EMAIL))
        {
            throw new InvalidValueException(Translate::t('The e-mail address is malformed.'));
        }

        // although technically valid, we don't accept e-mail adresses with capital letters
        if (strtolower($value) !== $value)
        {
            throw new InvalidValueException(Translate::t('The e-mail address must not contain capital letters.'));
        }
    }
}
