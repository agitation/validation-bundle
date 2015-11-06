<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DebugBundlesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('debug:bundles')
            ->setDescription('Dumps a list of all bundles');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        foreach ($container->getParameter('kernel.bundles') as $alias => $namespace)
            $output->writeln($alias);
    }
}
