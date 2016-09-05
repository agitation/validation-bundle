<?php
/**
 * @package    agitation/validation
 * @link       http://github.com/agitation/AgitValidationBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Plugin;

use Agit\ValidationBundle\Exception\InvalidValueException;
use Agit\ValidationBundle\Service\ValidationService;

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
