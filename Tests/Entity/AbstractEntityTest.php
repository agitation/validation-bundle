<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Tests\Entity;

use Agit\CoreBundle\Entity\AbstractEntity;

class AbstractEntityTest extends \PHPUnit_Framework_TestCase
{
    public function test__ToString()
    {
        $Entity = $this->createMockEntity();
        $this->assertEquals('123', $Entity->__toString());
    }

    public function testGetEntityClass()
    {
        $mockName = 'Dummy';
        $Entity = $this->createMockEntity($mockName);
        $this->assertEquals($mockName, $Entity->getEntityClass());
    }

    private function createMockEntity($mockName = null)
    {
        $mockInst = $this
            ->getMockBuilder('\Agit\CoreBundle\Entity\AbstractEntity')
            ->setMethods(['getId']);

        if ($mockName)
            $mockInst->setMockClassName('Dummy');

        $Entity = $mockInst->getMock();

        $Entity->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(123));

        return $Entity;
    }
}
