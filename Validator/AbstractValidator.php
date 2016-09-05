<?php

/*
 * @package    agitation/validation-bundle
 * @link       http://github.com/agitation/validation-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Validator;

abstract class AbstractValidator
{
    private $validationService;

    public function setValidationService(ValidationService $validationService)
    {
        $this->validationService = $validationService;
    }

    /**
     * Helper for accessing other validators.
     */
    final protected function getValidator($id)
    {
        return $this->validationService->getValidator($id);
    }

    /**
     * The actual validator method. Will, in many cases, have additional parameters.
     */
    abstract public function validate($value);
}
