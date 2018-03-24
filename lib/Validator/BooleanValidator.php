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

class BooleanValidator extends AbstractValidator
{
    public function validate($value, $acceptNull = false)
    {
        if ((! is_bool($value) && ! $acceptNull) || ($acceptNull && $value !== null))
        {
            throw new InvalidValueException(Translate::t('The value must be a boolean.'));
        }
    }
}
