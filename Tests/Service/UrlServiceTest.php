<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Tests\Service;

use Agit\CoreBundle\Service\UrlService;

class UrlServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testGetBackendDomain()
    {
        $urlService = $this->getServiceInstance();
        $this->assertEquals('backend.example.com', $urlService->getBackendDomain());
    }

    public function testGetFrontendDomain()
    {
        $urlService = $this->getServiceInstance();
        $this->assertEquals('frontend.example.com', $urlService->getFrontendDomain());
    }

    public function testGetCdnDomain()
    {
        $urlService = $this->getServiceInstance();
        $this->assertEquals('cdn.example.com', $urlService->getCdnDomain());
    }

    public function testCreateBackendUrl()
    {
        $urlService = $this->getServiceInstance();

        $this->assertEquals(
            'https://backend.example.com/foo/bar?a=b&c=d',
            $urlService->createBackendUrl('/foo/bar', ['a' => 'b', 'c' => 'd']));
    }

    public function testCreateFrontendUrl()
    {
        $urlService = $this->getServiceInstance();

        $this->assertEquals(
            'https://frontend.example.com/foo/bar?a=b&c=d',
            $urlService->createFrontendUrl('/foo/bar', ['a' => 'b', 'c' => 'd']));
    }

    public function testCreateCdnUrl()
    {
        $urlService = $this->getServiceInstance();

        $this->assertEquals(
            'https://cdn.example.com/foo/bar?a=b&c=d',
            $urlService->createCdnUrl('/foo/bar', ['a' => 'b', 'c' => 'd']));
    }

    public function testCreateUrl()
    {
        $urlService = $this->getServiceInstance();

        $this->assertEquals(
            'https://cdn.example.com/foo/bar?a=b&c=d',
            $urlService->createUrl('cdn', '/foo/bar', ['a' => 'b', 'c' => 'd']));
    }

    public function testAppend()
    {
        $urlService = $this->getServiceInstance();

        $this->assertEquals('/test?a=b&c=d', $urlService->append('/test', ['a' => 'b', 'c' => 'd']));
        $this->assertEquals('/test?a=b&amp;c=d', $urlService->append('/test', ['a' => 'b', 'c' => 'd'], 'html'));
        $this->assertEquals('/test?a=b%26c=d', $urlService->append('/test', ['a' => 'b', 'c' => 'd'], 'url'));
        $this->assertEquals('/test?a[]=a1&a[]=b1&c=d', $urlService->append('/test', ['a' => ['a1', 'b1'], 'c' => 'd']));
    }

    private function getServiceInstance()
    {
        return new UrlService('backend.example.com', 'frontend.example.com', 'cdn.example.com');
    }
}
