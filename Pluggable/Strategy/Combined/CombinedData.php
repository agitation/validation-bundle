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
 * Data container for fixture-aware objects to be registered.
 */
class CombinedData extends ObjectData
{
    private $fixtures = [];

    public function setFixtures($entityName, array $fixtures)
    {
        foreach ($fixtures as $fixture)
            if (!is_array($fixture))
                throw new InternalErrorException("Fixture data must be passed as arrays.");

        if (!isset($this->fixtures[$entityName]))
            $this->fixtures[$entityName] = [];

        $this->fixtures[$entityName] = $fixtures;
    }

    public function getFixtures($entityName)
    {
        return $this->fixtures[$entityName];
    }
}
