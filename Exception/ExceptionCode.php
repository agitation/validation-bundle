<?php
/**
 * @package    agitation/common
 * @link       http://github.com/agitation/AgitCommonBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CommonBundle\Exception;

/**
 * @Annotation
 *
 * What you see here are the the public error codes, i.e. errors where an
 * error is likely to be caused by the client, and presenting a verbose error
 * message is considered helpful. Everything else is handled and presented as
 * `InternalErrorException`.
 */
class ExceptionCode
{
    public $value = '0.99'; // there should always be a value, but just in case.

    public function __construct(array $options=null)
    {
        if ($options && isset($options['value']))
            $this->value = $options['value'];
    }

    public function getValue()
    {
        return $this->value;
    }
}
