<?php
/**
 * @package    agitation/cron
 * @link       http://github.com/agitation/AgitCronBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CronBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Agit\CoreBundle\Command\AbstractCommand;

class CronExecuteCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('agit:cron:execute')
            ->setDescription('Executes all registered cronjobs that are registered for the current cycle.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->flock(__FILE__)) return;
        $this->getContainer()->get('agit.cron')->run();
    }
}
