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
    /**
     * The actual validator method. Will, in many cases, have additional parameters.
     */
    abstract public function validate($value);
}
