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

class ObjectValidator extends AbstractValidator
{
    public function validate($object, array $properties = null, $onlyGivenProperties = true)
    {
        if (! is_object($object))
        {
            throw new InvalidValueException(Translate::t('The value must be an object.'));
        }

        if ($properties !== null)
        {
            $objectKeys = array_keys(get_object_vars($object));

            if ($onlyGivenProperties && count($objectKeys) !== count($properties))
            {
                throw new InvalidValueException(Translate::t('The object has an invalid set of properties.'));
            }

            foreach ($properties as $property)
            {
                if (! in_array($property, $objectKeys))
                {
                    throw new InvalidValueException(sprintf(Translate::t('The object is missing the mandatory “%s” property.'), $property));
                }
            }
        }
    }
}
