<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Service;

use Agit\BaseBundle\Exception\InvalidValueException;
use Agit\BaseBundle\Pluggable\Object\ObjectLoaderFactory;
use Agit\BaseBundle\Tool\Translate;

class ValidationService
{
    private $objectLoader;

    private $validatorList = [];

    public function __construct(ObjectLoaderFactory $objectLoaderFactory)
    {
        $this->objectLoader = $objectLoaderFactory->create("agit.validation");
    }

    // shortcut
    public function validate($id, $value)
    {
        call_user_func_array(
            [$this->getValidator($id), 'validate'],
            array_slice(func_get_args(), 1));
    }

    // shortcut. extends the error message with a reference to the field.
    public function validateField($fieldName, $id, $value)
    {
        try {
            call_user_func_array(
                [$this->getValidator($id), 'validate'],
                array_slice(func_get_args(), 2));
        } catch (\Exception $e) {
            throw new InvalidValueException(sprintf(Translate::t("Invalid value for %s: %s"), $fieldName, $e->getMessage()));
        }
    }

    // shortcut. ATTENTION: instead of throwing an exception, it returns true/false
    public function isValid($id, $value)
    {
        $isValid = false;

        try {
            call_user_func_array([$this, 'validate'], func_get_args());
            $isValid = true;
        } catch (\Exception $e) {
        }

        return $isValid;
    }

    public function getValidator($id)
    {
        if (! isset($validatorList[$id])) {
            $validatorList[$id] = $this->objectLoader->getObject($id);
            $validatorList[$id]->setValidationService($this);
        }

        return $validatorList[$id];
    }
}
