<?php
declare(strict_types=1);
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
     * @param mixed $value
     */
    abstract public function validate($value);
}
