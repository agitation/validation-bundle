<?php
/**
 * @package    agitation/intl
 * @link       http://github.com/agitation/AgitIntlBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\IntlBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Agit\CoreBundle\Command\AbstractCommand;

class GlobalCatalogCommand extends AbstractCommand
{
    private $globalLocSubdir = "Resources/translations";

    private $bundleCatalogSubdir = "Resources/translations";

    protected function configure()
    {
        $this
            ->setName('agit:intl:catalog:global')
            ->setDescription('Collects translatable strings from all bundles and generates a global translation catalog.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->flock(__FILE__)) return;

        $Container = $this->getContainer();
        $pathList = [];

        foreach ($Container->getParameter('kernel.bundles') as $alias => $namespace)
            $pathList[] = "@$alias";

        $res = $Container->get('agit.intl.catalog')->generateGlobalCatalog($pathList);

        if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity())
            print_r($res);
    }
}
