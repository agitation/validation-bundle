<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Plugin\Validator;

use Agit\BaseBundle\Exception\InvalidValueException;
use Agit\BaseBundle\Pluggable\Object\ObjectPlugin;
use Agit\BaseBundle\Tool\Translate;

/**
 * @ObjectPlugin(tag="agit.validation", id="array")
 */
class ArrayValidator extends AbstractValidator
{
    public function validate($value, $minLength = null, $maxLength = null, array $requireKeys = null, $allowOtherKeys = false)
    {
        if (! is_array($value)) {
            throw new InvalidValueException(Translate::t("The value must be an array."));
        }

        if (is_int($minLength) && count($value) < $minLength) {
            throw new InvalidValueException(sprintf(Translate::t("The array is too short, it must have at least %s elements."), $minLength));
        }

        if (is_int($maxLength) && count($value) > $maxLength) {
            throw new InvalidValueException(sprintf(Translate::t("The array is too long, it must have at most %s elements."), $maxLength));
        }

        if (! is_null($requireKeys)) {
            $keys = array_keys($value);

            if ($allowOtherKeys && count($keys) !== count($requireKeys)) {
                throw new InvalidValueException(Translate::t("The array has invalid keys."));
            }

            foreach ($requireKeys as $key) {
                if (! in_array($key, $keys)) {
                    throw new InvalidValueException(sprintf(Translate::t("The array is missing the mandatory `%s` key."), $key));
                }
            }
        }
    }
}
