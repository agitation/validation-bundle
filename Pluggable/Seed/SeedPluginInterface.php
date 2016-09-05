<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable\Seed;

interface SeedPluginInterface
{
    /**
     * This method is expected to collect all plugin entries into a stack and
     * keep them available for later retrieval with the `nextSeedEntry` method.
     */
    public function load();

    /**
     * This method is expected to return one SeedEntry object after another, or
     * `null` if the stack is empty.
     */
    public function nextSeedEntry();
}
