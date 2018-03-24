<?php
declare(strict_types=1);

/*
 * @package    agitation/validation-bundle
 * @link       http://github.com/agitation/validation-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle;

use Agit\IntlBundle\Tool\Translate;
use Agit\ValidationBundle\Exception\InvalidValueException;
use Agit\ValidationBundle\Validator\AbstractValidator;

class ValidationService
{
    private $validators = [];

    public function addValidator($id, AbstractValidator $validator)
    {
        $this->validators[$id] = $validator;
    }

    // shortcut
    public function validate($id, $value)
    {
        call_user_func_array(
            [$this->validators[$id], 'validate'],
            array_slice(func_get_args(), 1)
        );
    }

    // shortcut. extends the error message with a reference to the field.
    public function validateField($fieldName, $id, $value)
    {
        try
        {
            call_user_func_array(
                [$this->validators[$id], 'validate'],
                array_slice(func_get_args(), 2)
            );
        }
        catch (InvalidValueException $e)
        {
            throw new InvalidValueException(sprintf(Translate::t('Invalid value for %s: %s'), $fieldName, $e->getMessage()));
        }
    }

    // shortcut. ATTENTION: instead of throwing an exception, it returns true/false
    public function isValid($id, $value)
    {
        $isValid = false;

        try
        {
            call_user_func_array([$this, 'validate'], func_get_args());
            $isValid = true;
        }
        catch (InvalidValueException $e)
        {
        }

        return $isValid;
    }
}
