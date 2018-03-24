<?php
declare(strict_types=1);

/*
 * @package    agitation/validation-bundle
 * @link       http://github.com/agitation/validation-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Validator;

class PhoneNumberValidator extends AbstractValidator
{
    private $regexValidator;

    public function __construct(RegexValidator $regexValidator)
    {
        $this->regexValidator = $regexValidator;
    }

    public function validate($value)
    {
        $this->regexValidator->validate($value, "|^\+[1-9]\d{1,3}\-?\d{2,7}\-?\d{3,12}(-?\d{1,6})?$|");
    }
}
