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

class ScalarValidator extends AbstractValidator
{
    public function validate($value)
    {
        if (! is_scalar($value))
        {
            throw new InvalidValueException(Translate::t('The value must be scalar.'));
        }
    }
}
