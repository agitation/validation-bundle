<?php
/**
 * @package    agitation/common
 * @link       http://github.com/agitation/AgitBaseBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Indicates that an entity can be soft-deleted.
 * Should be used together with the DeletableTrait.
 */
interface DeletableInterface
{
    public function setDeleted($deleted);

    public function isDeleted();
}
