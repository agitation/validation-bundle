<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\Strategy\Seed;

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
