<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadPluginsCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('agit:core:plugins')
            ->setDescription('Loads and registers all pluggable services and their plugins.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->flock(__FILE__)) return;

        $PluginService = $this->getContainer()->get('agit.plugin.manager');
        $PluginService->warmUp();
    }
}
