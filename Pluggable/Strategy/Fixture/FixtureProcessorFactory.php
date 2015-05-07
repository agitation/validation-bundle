<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Fixture;

use Agit\CoreBundle\Exception\InternalErrorException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Validator;

/**
 * Creates an fixture processor instance.
 */
class FixtureProcessorFactory
{
    private $EntityManager;

    private $EntityValidator;

    public function __construct(EntityManager $EntityManager, Validator $EntityValidator)
    {
        $this->EntityManager = $EntityManager;
        $this->EntityValidator = $EntityValidator;
    }

    public function create($entityName, $priority, $removeObsolete = true, $updateExisting = true)
    {
        return new FixtureProcessor(
            $this->EntityManager,
            $this->EntityValidator,
            $entityName,
            $priority,
            $removeObsolete,
            $updateExisting
        );
    }
}
