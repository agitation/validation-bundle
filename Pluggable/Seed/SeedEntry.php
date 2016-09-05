<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable\Seed;

class SeedEntry
{
    // whether or not to update an entry in the DB if it exists
    private $update = true;

    private $data = [];

    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setDoUpdate($update)
    {
        $this->update = $update;
    }

    public function doUpdate()
    {
        return $this->update;
    }
}
