<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Service;

use Symfony\Component\ClassLoader\ClassMapGenerator;

class ClassCollector extends FileCollector
{
    private $resolved = [];

    /**
     * @param string $location            something like `FoobarBundle:Directory:Subdir`
     * @param bool   $ignoreBrokenClasses ignore classes that are broken e.g. due to a missing parent class
     */
    public function collect($location, $ignoreBrokenClasses = false)
    {
        // to catch broken files, we must register our own autoloader
        // otherwise, a fatal error would be thrown.

        $fallbackAutoloader = [$this, 'fallbackAutoloader'];
        spl_autoload_register($fallbackAutoloader);

        $files = parent::collect($location, 'php');
        $classes = [];

        foreach ($files as $file) {
            try {
                $className = $this->getFullClass($file);
                if (! $className) {
                    continue;
                }

                $classExists = class_exists($className);

                if ($classExists) {
                    $refl = new \ReflectionClass($className);
                    if ($refl->isAbstract()) {
                        continue;
                    }

                    $classes[] = $className;
                }
            } catch (\Exception $e) {
                if (! $ignoreBrokenClasses) {
                    throw $e;
                }
            }
        }

        spl_autoload_unregister($fallbackAutoloader);

        return $classes;
    }

    public function fallbackAutoloader($className)
    {
        throw new \Exception("Class $className could not be loaded!");
    }

    private function getFullClass($file)
    {
        $dir = dirname($file);

        if (! isset($this->resolved[$file])) {
            $this->resolved += array_flip(ClassMapGenerator::createMap($dir));
        }

        return isset($this->resolved[$file]) ? $this->resolved[$file] : '';
    }
}
