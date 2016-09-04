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
use Agit\PluggableBundle\Strategy\Object\ObjectPlugin;
use Agit\BaseBundle\Tool\Translate;

/**
 * @ObjectPlugin(tag="agit.validation", id="multiSelection")
 */
class MultiSelectionValidator extends AbstractValidator
{
    public function validate($value, $possibleValues = [])
    {
        if (!is_array($value))
            throw new InvalidValueException(Translate::t("The value must be an array."));

        foreach($value as $val)
            if (!in_array($val, $possibleValues))
                throw new InvalidValueException(sprintf(
                    Translate::t("The value must be one of the following: “%s”."),
                    implode(Translate::x("quotation inside collection", "”, “"),
                    $possibleValues)
                ));
    }
}
