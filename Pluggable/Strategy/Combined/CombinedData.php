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

/**
 * Data container for objects to be registered.
 */
class CombinedData
{
    private $id;

    private $class;

    private $fixtures = [];

    public function setId($id)
    {
        if (!is_string($id))
            throw new InternalErrorException("The ID must be a string.");

        $this->id = $id;
    }

    public function setClass($class)
    {
        $this->class = $class;
    }

    public function setFixtures($entityName, array $fixtures)
    {
        foreach ($fixtures as $fixture)
        {
            if (!is_array($fixture))
                throw new InternalErrorException("Fixture data must be passed as arrays.");

            foreach ($fixture as $value)
                if (!is_scalar($value))
                    throw new InternalErrorException("Fixture values must be scalar.");
        }

        if (!isset($this->fixtures[$entityName]))
            $this->fixtures[$entityName] = [];

        $this->fixtures[$entityName] = $fixtures;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getFixtures($entityName)
    {
        return $this->fixtures[$entityName];
    }
}