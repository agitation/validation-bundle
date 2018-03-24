<?php
declare(strict_types=1);

/*
 * @package    agitation/validation-bundle
 * @link       http://github.com/agitation/validation-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Exception;

use Agit\BaseBundle\Exception\PublicException;

/**
 * The validation of a value has failed, see the message field for details.
 */
class InvalidValueException extends PublicException
{
    protected $statusCode = 400;
}
