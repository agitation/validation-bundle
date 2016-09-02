<?php
/**
 * @package    agitation/common
 * @link       http://github.com/agitation/AgitBaseBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Tests\Service;

use Agit\BaseBundle\Service\UrlService;

class UrlServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAppDomain()
    {
        $urlService = $this->getServiceInstance();
        $this->assertEquals('app.example.com', $urlService->getAppDomain());
    }

    public function testGetCdnDomain()
    {
        $urlService = $this->getServiceInstance();
        $this->assertEquals('cdn.example.com', $urlService->getCdnDomain());
    }

    public function testCreateAppUrl()
    {
        $urlService = $this->getServiceInstance();

        $this->assertEquals(
            'https://app.example.com/foo/bar?a=b&c=d',
            $urlService->createAppUrl('/foo/bar', ['a' => 'b', 'c' => 'd']));
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
        return new UrlService('app.example.com', 'cdn.example.com', true);
    }
}
