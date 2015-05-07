<?php
/**
 * @package    agitation/validation
 * @link       http://github.com/agitation/AgitValidationBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests;

use Agit\CoreBundle\Tests\AbstractContainerAwareTest;

class ValidationServiceIntegrationTest extends AbstractContainerAwareTest
{
    public function testValidationServiceWorks()
    {
//         $ValidationService = $this->getContainer()->get('agit.validation');
//         $ValidationService->warmUp();
//         $ValidationService->getValidator('string')->validate('foobar');
    }

    public function testValidationServiceException()
    {
//         $this->setExpectedException('\Agit\ValidationBundle\Exception\InvalidValueException');
//         $ValidationService = $this->getContainer()->get('agit.validation');
//         $ValidationService->warmUp();
//         $ValidationService->getValidator('string')->validate(42);
    }
}
