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

class LongitudeValidator extends AbstractValidator
{
    public function validate($value)
    {
        if ((! is_float($value) && ! is_int($value)) || $value < -180 || $value > 180)
        {
            throw new InvalidValueException(Translate::t('The longitude is invalid.'));
        }
    }
}
