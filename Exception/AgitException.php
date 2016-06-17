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
 * The mother of all Agitation exceptions.
 *
 * NOTE: When extending this class, remember to set an appropriate status code.
 */
abstract class AgitException extends \Exception
{
    protected $httpStatus = 500;

    /**
     * Returns an HTTP status which indicates the type of error.
     *
     * @return integer the numeric HTTP status code.
     */
    public function getHttpStatus()
    {
        return $this->httpStatus;
    }
}
