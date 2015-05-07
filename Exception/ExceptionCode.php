<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Exception;

/**
 * @Annotation
 *
 * What you see here are the the public error codes, i.e. errors where an
 * error is likely to be caused by the client, and presenting a verbose error
 * message is considered helpful. Everything else is handled and presented as
 * `InternalErrorException`.
 *
 * All error codes consist of two parts, separated by a dot:
 *
 * - category: a logical group of functionality
 * - number: the serial number of the exception within that group
 *
 * **Public categories**:
 *
 * -  0, _core_. Generic exceptions and internal errors.
 * -  1, _engine_. Routing and pricing engine.
 * -  2, _payment_. Payment module configuration and payment processing.
 * -  3, _booking_. Booking and ticket management.
 * -  4, _api_. API endpoints, objects and formatters.
 * -  5, _settings_. Shop settings, unless they fall into a different category (e.g. payment).
 * -  6, _mailer_. Outgoing mail, synchronously or spooled.
 * -  7, _ui_. Front-end pages and user interfaces.
 * -  8, _mailer_. The mailer component.
 * -  9, _newsletter_. The newsletter component.
 * - 99, _various_. Services or components that don't belong to any other category.
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