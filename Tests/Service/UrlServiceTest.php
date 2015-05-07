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
        $UrlService = $this->getServiceInstance();
        $this->assertEquals('backend.example.com', $UrlService->getBackendDomain());
    }

    public function testGetFrontendDomain()
    {
        $UrlService = $this->getServiceInstance();
        $this->assertEquals('frontend.example.com', $UrlService->getFrontendDomain());
    }

    public function testGetCdnDomain()
    {
        $UrlService = $this->getServiceInstance();
        $this->assertEquals('cdn.example.com', $UrlService->getCdnDomain());
    }

    public function testCreateBackendUrl()
    {
        $UrlService = $this->getServiceInstance();

        $this->assertEquals(
            'https://backend.example.com/foo/bar?a=b&c=d',
            $UrlService->createBackendUrl('/foo/bar', ['a' => 'b', 'c' => 'd']));
    }

    public function testCreateFrontendUrl()
    {
        $UrlService = $this->getServiceInstance();

        $this->assertEquals(
            'https://frontend.example.com/foo/bar?a=b&c=d',
            $UrlService->createFrontendUrl('/foo/bar', ['a' => 'b', 'c' => 'd']));
    }

    public function testCreateCdnUrl()
    {
        $UrlService = $this->getServiceInstance();

        $this->assertEquals(
            'https://cdn.example.com/foo/bar?a=b&c=d',
            $UrlService->createCdnUrl('/foo/bar', ['a' => 'b', 'c' => 'd']));
    }

    public function testCreateUrl()
    {
        $UrlService = $this->getServiceInstance();

        $this->assertEquals(
            'https://cdn.example.com/foo/bar?a=b&c=d',
            $UrlService->createUrl('cdn', '/foo/bar', ['a' => 'b', 'c' => 'd']));
    }

    public function testAppend()
    {
        $UrlService = $this->getServiceInstance();

        $this->assertEquals('/test?a=b&c=d', $UrlService->append('/test', ['a' => 'b', 'c' => 'd']));
        $this->assertEquals('/test?a=b&amp;c=d', $UrlService->append('/test', ['a' => 'b', 'c' => 'd'], 'html'));
        $this->assertEquals('/test?a=b%26c=d', $UrlService->append('/test', ['a' => 'b', 'c' => 'd'], 'url'));
        $this->assertEquals('/test?a[]=a1&a[]=b1&c=d', $UrlService->append('/test', ['a' => ['a1', 'b1'], 'c' => 'd']));
    }

    private function getServiceInstance()
    {
        return new UrlService('backend.example.com', 'frontend.example.com', 'cdn.example.com');
    }
}
