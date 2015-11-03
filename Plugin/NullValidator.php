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

/**
 * @ObjectPlugin(tag="agit.validation", id="null")
 */
class NullValidator extends AbstractValidator
{
    public function validate($value)
    {
        if (!is_null($value))
            throw new InvalidValueException($this->translate->t("The value must be NULL."));
    }
}
