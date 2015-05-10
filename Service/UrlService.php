<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Service;

use Agit\CoreBundle\Exception\InternalErrorException;

class UrlService
{
    private $domains = [];

    private $protocol;

    public function __construct($backendDomain, $frontendDomain, $cdnDomain, $forceHttps)
    {
        $this->domains = [
            'backend' => $backendDomain,
            'frontend' => $frontendDomain,
            'cdn' => $cdnDomain];

        $this->protocol = ($forceHttps || (isset($_SERVER['SERVER_PROTOCOL']) && stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true))
            ? 'https' : 'http';
    }

    public function getBackendDomain()
    {
        return $this->domains['backend'];
    }

    public function getFrontendDomain()
    {
        return $this->domains['frontend'];
    }

    public function getCdnDomain()
    {
        return $this->domains['cdn'];
    }

    public function createBackendUrl($path = '', array $params = [])
    {
        return $this->createUrl('backend', $path, $params);
    }

    public function createFrontendUrl($path = '', array $params = [])
    {
        return $this->createUrl('frontend', $path, $params);
    }

    public function createCdnUrl($path = '', array $params = [])
    {
        return $this->createUrl('cdn', $path, $params);
    }

    public function createUrl($type, $path = '', array $params = [])
    {
        if (!isset($this->domains[$type]))
            throw new InternalErrorException("Invalid domain type");

        $url = sprintf("%s://%s/%s", $this->protocol, $this->domains[$type], ltrim($path, '/'));

        if (count($params))
            $url = $this->append($url, $params);

        return $url;
    }

    /**
     * append request parameters to a given URL
     */
    public function append($url, array $params, $enctype='')
    {
        if ($enctype === 'html')
            $amp = '&amp;';
        elseif ($enctype === 'url')
            $amp = '%26';
        else
            $amp = '&';

        foreach ($params as $key=>$value)
        {
            if (is_array($value))
            {
                $key     .= "[]";
                $urlpart  = array();

                foreach ($value as $val)
                    $urlpart[] = "$key=$val";

                $urlpart = implode($amp, $urlpart);
            }
            else
            {
                $urlpart = "$key=$value";
            }

            $url .= (strpos($url, '?') ? $amp : '?').$urlpart;
        }

        return $url;
    }
}
