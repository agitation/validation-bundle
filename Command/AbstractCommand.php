<?php
/**
 * @package    agitation/common
 * @link       http://github.com/agitation/AgitCommonBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CommonBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Agit\CommonBundle\Exception\AgitException;

abstract class AbstractCommand extends ContainerAwareCommand
{
    private $fileHandle; // used for "singleton" locks

    // use this to allow only one instance of this command
    protected function flock($scriptfile)
    {
        $this->fileHandle = fopen($scriptfile, 'r');
        $ok = flock($this->fileHandle, LOCK_EX | LOCK_NB);

        if (!$ok) print("Another instance is already running.\n");

        return $ok;
    }
}
