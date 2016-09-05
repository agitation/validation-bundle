<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable\Seed;

class SimpleSeedPlugin implements SeedPluginInterface
{
    private $data = [];

    public function __construct(array $data, $doUpdate)
    {
        foreach ($data as $entry) {
            $seedEntry = new SeedEntry();
            $seedEntry->setData($entry);
            $seedEntry->setDoUpdate($doUpdate);
            $this->data[] = $seedEntry;
        }
    }

    public function load()
    {
    }

    public function nextSeedEntry()
    {
        return array_pop($this->data);
    }
}
