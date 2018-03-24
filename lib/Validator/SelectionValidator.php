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

class SelectionValidator extends AbstractValidator
{
    public function validate($value, array $possibleValues = [])
    {
        if (! in_array($value, $possibleValues))
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
