<?php
/**
 * @package    agitation/common
 * @link       http://github.com/agitation/AgitCommonBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CommonBundle\Entity;

use Agit\CommonBundle\Exception\InternalErrorException;

abstract class AbstractEntity
{
    /**
     * This is only used for comparison and sorting
     *
     * @return string stringified object's ID
     */
    public function __toString()
    {
        return (string)$this->getId();
    }

    public function getEntityClass()
    {
        $className = get_class($this);

        if (strpos($className, 'Prox') !== false)
            $className = get_parent_class($this);

        if (strpos($className, '\\') !== false)
            $className = substr(strrchr($className, "\\"), 1);

        return $className;
    }

    public function getEntityName()
    {
        return $this->getEntityClass(); // until we have something better
    }

    abstract public function getId();
}
