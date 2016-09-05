<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable\Entity;

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
