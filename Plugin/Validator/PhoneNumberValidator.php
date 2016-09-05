<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Plugin\Validator;

use Agit\BaseBundle\Pluggable\Object\ObjectPlugin;

/**
 * @ObjectPlugin(tag="agit.validation", id="phoneNumber")
 */
class PhoneNumberValidator extends AbstractValidator
{
    public function validate($value)
    {
        $this->getValidator('regex')->validate($value, '|^\+[1-9]\d{1,3}\-?\d{2,7}\-?\d{3,12}(-?\d{1,6})?$|');
    }
}
