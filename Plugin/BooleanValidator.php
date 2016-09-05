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
use Agit\BaseBundle\Pluggable\Object\ObjectPlugin;
use Agit\BaseBundle\Tool\Translate;

/**
 * @ObjectPlugin(tag="agit.validation", id="boolean")
 */
class BooleanValidator extends AbstractValidator
{
    public function validate($value, $acceptNull = false)
    {
        if ( (!is_bool($value) && !$acceptNull) || ($acceptNull && $value !== null))
            throw new InvalidValueException(Translate::t("The value must be a boolean."));
    }
}
