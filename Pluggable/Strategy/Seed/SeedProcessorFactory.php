<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Seed;

use Agit\CoreBundle\Exception\InternalErrorException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Validator;

/**
 * Creates an seed processor instance.
 */
class SeedProcessorFactory
{
    private $entityManager;

    private $entityValidator;

    public function __construct(EntityManager $entityManager, Validator $entityValidator)
    {
        $this->entityManager = $entityManager;
        $this->entityValidator = $entityValidator;
    }

    public function create($entityName, $priority, $removeObsolete = true, $updateExisting = true)
    {
        return new SeedProcessor(
            $this->entityManager,
            $this->entityValidator,
            $entityName,
            $priority,
            $removeObsolete,
            $updateExisting
        );
    }
}
