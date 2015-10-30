<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Service;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Finder\Finder;

class FileCollector
{
    private $fileLocator;

    public function __construct(FileLocator $fileLocator)
    {
        $this->fileLocator = $fileLocator;
    }

    /**
     * @param string $location, something like `FoobarBundle:Directory:Subdir`
     */
    public function resolve($location)
    {
        $path = null;

        try
        {
            if ($location[0] !== '@')
                $location = "@$location";

            $path = $this->fileLocator->locate(str_replace(':', '/', $location));
        }
        catch (\Exception $e) {}

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
}
