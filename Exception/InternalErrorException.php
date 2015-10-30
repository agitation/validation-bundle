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
 * @ExceptionCode("agit.core.internal")
 *
 * We've messed something up internally, and now a certain process cannot
 * continue. The error will be logged, but the returned message is generic.
 */
class InternalErrorException extends AgitException
{
    public static $container;

    protected $loggerService;

    private $eNum = 0;

    private $actualMessage;

    private $debug = false;

    public function __construct($msg, $code=0, $prev=null)
    {
        parent::__construct($msg, $code, $prev);

        $this->actualMessage = "InternalErrorException: $msg";

        if (static::$container)
            $this->debug = static::$container->getParameter('kernel.debug');

//         if (!$this->debug)
//             $this->message = $this->translate->t("Sorry, there has been an internal error. The administrators have been notified and will fix this as soon as possible.");

        if (static::$container)
            $this->loggerService = static::$container->get('logger');

        $currentE = $this;
        $prevE = null;

        while ($currentE && $currentE != $prevE)
        {
            $this->logException($currentE);
            $prevE = $currentE;
            $currentE = $this->getPrevious();
        }
    }

    private function logException(\Exception $e)
    {
        $msg = sprintf("+++ EXCEPTION HISTORY, %s: %s\n\nTRACE:\n%s\n\n\n\n",
            ++$this->eNum,
            $e === $this ? $this->actualMessage : $e->getMessage(),
            $e->getTraceAsString());

        if (!$this->debug && $this->loggerService)
            $this->loggerService->err($msg);
        elseif ($this->debug)
            echo $msg;
    }
}
