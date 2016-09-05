<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CronExecuteCommand extends ContainerAwareCommand
{
    use SingletonCommandTrait;

    protected function configure()
    {
        $this
            ->setName("agit:cronjobs:execute")
            ->setDescription("Executes all registered cronjobs that are registered for the current cycle.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (! $this->flock(__FILE__)) {
            return;
        }
        $this->getContainer()->get("agit.cron")->run();
    }
}
