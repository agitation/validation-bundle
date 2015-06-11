<?php
/**
 * @package    agitation/intl
 * @link       http://github.com/agitation/AgitIntlBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\IntlBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * This event is triggered after the files for generating a global catalog have
 * been collected and merged with the main catalog. Listeners can use this hook
 * to clean up temporary files.
 *
 * This event does not offer any registration methods, as it is only used for
 * notification.
 */
class CatalogCleanupEvent extends Event
{
}
