<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Entity;

trait IdentityAwareTrait
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * This is mostly useful for comparison and sorting.
     */
    public function __toString()
    {
        return get_class() . '-' . (string)$this->getId();
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

    // can be overridden with something that returns the natural, localized entity name.
    public function getEntityName()
    {
        return $this->getEntityClass();
    }
}
