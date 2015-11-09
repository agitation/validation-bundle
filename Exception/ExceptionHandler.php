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
 * This class handles Fatal Exceptions and other non-runtime errors.
 */
class ExceptionHandler
{
    static public function errorToException($code, $message, $file='(unknown)', $line='(unknown)')
    {
        throw new InternalErrorException(sprintf(
            " %s in %s line %s",
            $message, $file, $line
        ), $code);
    }

    static public function convert(\Exception $e)
    {
        throw new InternalErrorException($e->getMessage(), $e->getCode(), $e->getPrevious());
    }

    static public function shutdown()
    {
        if ($error = error_get_last())
        {
            // if an error pops up to this point, we do the routine of a "common" InternalErrorException
            // then die with a simple error message.
            try
            {
                throw new InternalErrorException(sprintf(
                    " %s in %s line %s",
                    $error['message'], $error['file'], $error['line']
                ));
            }
            catch(\Exception $e)
            {
//                 p($e->getMessage());
                die("Sorry, there's been an internal error. The administrators have been notified of this and will fix it as soon as possible.");
            }
        }
    }
}
