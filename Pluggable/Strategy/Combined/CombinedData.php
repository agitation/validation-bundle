<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Combined;

use Agit\CoreBundle\Exception\InternalErrorException;
use Agit\CoreBundle\Pluggable\Strategy\Object\ObjectData;

/**
 * Data container for seed-aware objects to be registered.
 */
class CombinedData extends ObjectData
{
    private $seeds = [];

    public function setSeeds($entityName, array $seeds)
    {
        foreach ($seeds as $seed)
            if (!is_array($seed))
                throw new InternalErrorException("Seed data must be passed as arrays.");

        if (!isset($this->seeds[$entityName]))
            $this->seeds[$entityName] = [];

        $this->seeds[$entityName] = $seeds;
    }

    public function getSeeds($entityName)
    {
        return $this->seeds[$entityName];
    }
}
