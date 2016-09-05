<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Validator;

use Agit\ValidationBundle\Exception\InvalidValueException;
use Agit\BaseBundle\Tool\Translate;

class EmailValidator extends AbstractValidator
{
    public function validate($value)
    {
        if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidValueException(Translate::t("The e-mail address is malformed."));
        }

        // although technically valid, we don't accept e-mail adresses with capital letters
        if (strtolower($value) !== $value) {
            throw new InvalidValueException(Translate::t("The e-mail address must not contain capital letters."));
        }
    }
}
