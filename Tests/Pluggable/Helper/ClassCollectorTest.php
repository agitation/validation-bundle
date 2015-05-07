<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Tests\Helper;

use Agit\CoreBundle\Service\ClassCollector;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClassCollectorTest extends WebTestCase
{
    // functional test, because of dependency on Kernel
    // (the FileLocator used by ClassCollector must resolve the paths of registered bundles)
    public function testCollect()
    {
        $dummyNamespace = 'AgitCoreBundle:Tests:Pluggable:Helper:_data';

        $Kernel = static::createKernel();
        $Kernel->boot();

        $ClassCollector = new ClassCollector(new FileLocator($Kernel));
        $fileList = $ClassCollector->collect($dummyNamespace);

        sort($fileList);

        $expected = [
            '\Agit\CoreBundle\Tests\Helper\_data\DummyFile1',
            '\Agit\CoreBundle\Tests\Helper\_data\DummyFile2'
        ];

        $this->assertEquals($expected, $fileList);
    }
}
