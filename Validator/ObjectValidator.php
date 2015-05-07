<?php
/**
 * @package    agitation/validation
 * @link       http://github.com/agitation/AgitValidationBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Validator;

use Agit\ValidationBundle\Exception\InvalidValueException;

class ObjectValidator extends AbstractValidator
{
    public function validate($object, array $properties = null, $onlyGivenProperties = true)
    {
        if (!is_object($object))
            throw new InvalidValueException($this->translate->t("The value must be an object."));

        if (!is_null($properties))
        {
            $objectKeys = array_keys(get_object_vars($object));

            if ($onlyGivenProperties && count($objectKeys) !== count($properties))
                throw new InvalidValueException($this->translate->t("The object has an invalid set of properties."));

            foreach ($properties as $property)
                if (!in_array($property, $objectKeys))
                    throw new InvalidValueException(sprintf($this->translate->t("The object is missing the mandatory “%s” property."), $property));
        }
    }
}