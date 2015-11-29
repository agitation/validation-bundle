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
use Agit\CommonBundle\Command\SingletonCommandTrait;

class BundleCatalogCommand extends AbstractCatalogCommand
{
    use SingletonCommandTrait;

    private $globalLocSubdir = "Resources/translations";

    private $bundleCatalogSubdir = "Resources/translations";

    protected function configure()
    {
        $this
            ->setName('agit:intl:catalog:bundle')
            ->setDescription('Collects translatable strings from a bundle and generates a translation catalog for it.')
            ->addArgument('bundle', InputArgument::REQUIRED, 'bundle alias, e.g. AcmeFoobarBundle');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->flock(__FILE__)) return;

        $res = $this->getContainer()
            ->get('agit.intl.catalog')
            ->generateBundleCatalog($input->getArgument('bundle'));

        if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity() && count($res))
        {
            $this->summary($output, $res);
        }
    }
}
