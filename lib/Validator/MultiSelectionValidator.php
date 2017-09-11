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

class MultiSelectionValidator extends AbstractValidator
{
    public function validate($value, $possibleValues = [])
    {
        if (! is_array($value))
        {
            throw new InvalidValueException(Translate::t('The value must be an array.'));
        }

        foreach ($value as $val)
        {
            if (! in_array($val, $possibleValues))
            {
                throw new InvalidValueException(sprintf(
                    Translate::t('The value must be one of the following: “%s”.'),
                    implode(
                        Translate::x('quotation inside collection', '”, “'),
                    $possibleValues
                    )
                ));
            }
        }
    }
}
