<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Object;

use Agit\CoreBundle\Exception\InternalErrorException;

/**
 * Data container for objects to be registered.
 */
class ObjectData
{
    private $id;

    private $class;

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

    public function getId()
    {
        return $this->id;
    }

    public function getClass()
    {
        return $this->class;
    }
}
