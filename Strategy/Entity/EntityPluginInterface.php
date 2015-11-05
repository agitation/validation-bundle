<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\Strategy\Entity;

interface EntityPluginInterface
{
    /**
     * This method is expected to collect all database entries into a stack and
     * keep them available for later retrieval with the `nextSeedEntry` method.
     */
    public function loadSeedData();

    /**
     * This method is expected to return one seed entry (for the entity of the given name)
     * after another, or `null` if the stack for that entity name is empty.
     */
    public function nextSeedEntry($entityName);

    /**
     * This method will inject the entity object as loaded from the database
     * on plugin initialisation.
     */
    public function setEntity($entityObject);
}
