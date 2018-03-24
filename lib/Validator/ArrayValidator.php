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

class ArrayValidator extends AbstractValidator
{
    public function validate($value, $minLength = null, $maxLength = null, array $requireKeys = null, $allowOtherKeys = false)
    {
        if (! is_array($value))
        {
            throw new InvalidValueException(Translate::t('The value must be an array.'));
        }

        if (is_int($minLength) && count($value) < $minLength)
        {
            throw new InvalidValueException(sprintf(Translate::t('The list is too short, it must have at least %s elements.'), $minLength));
        }

        if (is_int($maxLength) && count($value) > $maxLength)
        {
            throw new InvalidValueException(sprintf(Translate::t('The list is too long, it must have at most %s elements.'), $maxLength));
        }

        if ($requireKeys !== null)
        {
            $keys = array_keys($value);

            if ($allowOtherKeys && count($keys) !== count($requireKeys))
            {
                throw new InvalidValueException(Translate::t('The list has invalid keys.'));
            }

            foreach ($requireKeys as $key)
            {
                if (! in_array($key, $keys))
                {
                    throw new InvalidValueException(sprintf(Translate::t('The list is missing the mandatory `%s` key.'), $key));
                }
            }
        }
    }
}
