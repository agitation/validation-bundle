<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Exception;

use Agit\BaseBundle\Exception\AgitException;

/**
 * The validation of a value has failed, see the message field for details.
 */
class InvalidValueException extends AgitException
{
    protected $httpStatus = 400;
}
