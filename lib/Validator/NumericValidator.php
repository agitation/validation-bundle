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

class NumericValidator extends AbstractValidator
{
    public function validate($value, $positiveInt = true)
    {
        if (! is_numeric($value))
        {
            throw new InvalidValueException(Translate::t('The value must be numeric.'));
        }

        if ($positiveInt === true && preg_match('|[^\d]|', $value))
        {
            throw new InvalidValueException(Translate::t('The value must be a positive integer number.'));
        }
    }
}
