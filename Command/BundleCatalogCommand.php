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

class BundleCatalogCommand extends AbstractCommand
{
    private $globalLocSubdir = "Resources/translations";

    private $bundleCatalogSubdir = "Resources/translations";

    protected function configure()
    {
        $this
            ->setName('agit:intl:catalog:bundle')
            ->setDescription('Collects translatable strings from a bundle and generates a translation catalog for it.')
            ->addArgument('path', InputArgument::REQUIRED, 'path to bundle, or bundle alias');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->flock(__FILE__)) return;

        $this->getContainer()
            ->get('agit.intl.catalog')
            ->generateBundleCatalog($input->getArgument('path'));
    }
}
