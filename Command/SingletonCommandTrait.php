<?php
/**
 * @package    agitation/common
 * @link       http://github.com/agitation/AgitCommonBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CommonBundle\Command;

trait SingletonCommandTrait
{
    private $selfFileHandle; // used for "singleton" locks

    // use this to allow only one instance of this command
    protected function flock($scriptfile)
    {
        $this->selfFileHandle = fopen($scriptfile, 'r');
        $ok = flock($this->selfFileHandle, LOCK_EX | LOCK_NB);

        if (!$ok) print("Another instance is already running.\n");

        return $ok;
    }
}
