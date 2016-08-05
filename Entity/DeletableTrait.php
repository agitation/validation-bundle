<?php
/**
 * @package    agitation/common
 * @link       http://github.com/agitation/AgitCommonBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Provides the capability for soft-deleting entities.
 * Must be used together with the DeletableInterface to make sense.
 */
trait DeletableTrait
{
    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull
     */
    protected $deleted = false;

    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }

    /**
     * Get deleted
     *
     * @return smallint
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Get deleted
     *
     * @return smallint
     */
    public function isDeleted()
    {
        return $this->deleted;
    }
}
