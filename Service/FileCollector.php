<?php
/**
 * @package    agitation/common
 * @link       http://github.com/agitation/AgitCommonBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CommonBundle\Service;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Finder\Finder;

class FileCollector
{
    private $kernel;

    private $namespaces;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @param string $location, namespace or something like `FoobarBundle:Directory:Subdir`
     */
    public function resolve($location)
    {
        $path = null;

        try
        {
            if (strpos($location, '\\') !== false)
            {
                $location = trim($location, '\\');

                foreach ($this->getNamespaces() as $name => $namespace)
                {
                    if (strpos($location, $namespace) === 0)
                    {
                        $location = str_replace($namespace, $name, $location);
                        $location = str_replace('\\', '/', "@$location");
                        $path = $this->kernel->locateResource($location);
                        break;
                    }
                }
            }
            else // assuming a namespace alias in colon notation, or just a bundle name
            {
                if ($location[0] !== '@')
                    $location = "@$location";

                $path = $this->kernel->locateResource(str_replace(':', '/', $location));
            }
        }
        catch (\Exception $e) { }

        return $path;
    }

    /**
     * @param string $location, something like `FoobarBundle:Directory:Subdir`
     * @param string $extension, something like `php` or `html.twig`
     */
    public function collect($location, $extension)
    {

        $path = $location[0] === '/' ? $location : $this->resolve($location);
        $files = [];

        if ($path)
        {
            $finder = new Finder();
            $finder->in($path)->name("*.$extension");

            foreach ($finder as $file)
                $files[] = $file->getRealpath();
        }

        return $files;
    }

    private function getNamespaces()
    {
        if (is_null($this->namespaces))
        {
            $this->namespaces = [];

            foreach ($this->kernel->getBundles() as $name => $bundle)
                $this->namespaces[$name] = $bundle->getNamespace();
        }

        return $this->namespaces;
    }
}
