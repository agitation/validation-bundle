<?php
/**
 * @package    agitation/intl
 * @link       http://github.com/agitation/AgitIntlBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\IntlBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Agit\CommonBundle\Command\SingletonCommandTrait;

abstract class AbstractCatalogCommand extends ContainerAwareCommand
{
    protected function summary($outputHandler, $result)
    {
        foreach ($result as $file => $stats)
        {
            $style = $stats['total'] === $stats['translated']
                ? 'fg=green;options=bold'
                : 'fg=yellow;options=bold';

            $outputHandler->writeln(sprintf(
                "<fg=cyan;options=bold>%s</fg=cyan;options=bold>: total: %5d, translated: <%s>%5d</%s>",
                $file,
                $stats['total'],
                $style,
                $stats['translated'],
                $style
            ));
        }
    }
}
