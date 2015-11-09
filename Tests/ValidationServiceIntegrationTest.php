<?php
/**
 * @package    agitation/validation
 * @link       http://github.com/agitation/AgitValidationBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests;

use Agit\CommonBundle\Tests\AbstractContainerAwareTest;

class ValidationServiceIntegrationTest extends AbstractContainerAwareTest
{
    public function testValidationServiceWorks()
    {
//         $validationService = $this->getContainer()->get('agit.validation');
//         $validationService->warmUp();
//         $validationService->getValidator('string')->validate('foobar');
    }

    public function testValidationServiceException()
    {
//         $this->setExpectedException('\Agit\ValidationBundle\Exception\InvalidValueException');
//         $validationService = $this->getContainer()->get('agit.validation');
//         $validationService->warmUp();
//         $validationService->getValidator('string')->validate(42);
    }
}
