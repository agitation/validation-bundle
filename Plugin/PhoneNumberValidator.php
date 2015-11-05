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
 * @ObjectPlugin(tag="agit.validation", id="phoneNumber")
 */
class PhoneNumberValidator extends AbstractValidator
{
    public function validate($value)
    {
        $this->getValidator('regex')->validate($value, '|^\+[1-9]\d{1,3}\-?\d{2,7}\-?\d{3,12}(-?\d{1,6})?$|');
    }
}
