<?php
/**
 * @package    agitation/common
 * @link       http://github.com/agitation/AgitCommonBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CommonBundle\Tests\Entity;

use Agit\CommonBundle\Entity\IdentityAwareTrait;

class AbstractEntityTest extends \PHPUnit_Framework_TestCase
{
    public function test__ToString()
    {
        $entity = $this->createMockEntity();
        $this->assertEquals('123', $entity->__toString());
    }

    public function testGetEntityClass()
    {
        $mockName = 'Dummy';
        $entity = $this->createMockEntity($mockName);
        $this->assertEquals($mockName, $entity->getEntityClass());
    }

    private function createMockEntity($mockName = null)
    {
        $mockInst = $this
            ->getMockBuilder('\Agit\CommonBundle\Entity\IdentityAwareTrait')
            ->setMethods(['getId']);

        if ($mockName)
            $mockInst->setMockClassName('Dummy');

        $entity = $mockInst->getMock();

        $entity->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(123));

        return $entity;
    }
}
