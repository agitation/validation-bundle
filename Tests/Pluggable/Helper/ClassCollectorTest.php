<?php
/**
 * @package    agitation/common
 * @link       http://github.com/agitation/AgitCommonBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CommonBundle\Tests\Helper;

use Agit\CommonBundle\Service\ClassCollector;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClassCollectorTest extends WebTestCase
{
    // functional test, because of dependency on Kernel
    // (the FileLocator used by ClassCollector must resolve the paths of registered bundles)
    public function testCollect()
    {
        $dummyNamespace = 'AgitCommonBundle:Tests:Pluggable:Helper:_data';

        $kernel = static::createKernel();
        $kernel->boot();

        $classCollector = new ClassCollector(new FileLocator($kernel));
        $fileList = $classCollector->collect($dummyNamespace);

        sort($fileList);

        $expected = [
            '\Agit\CommonBundle\Tests\Helper\_data\DummyFile1',
            '\Agit\CommonBundle\Tests\Helper\_data\DummyFile2'
        ];

        $this->assertEquals($expected, $fileList);
    }
}
