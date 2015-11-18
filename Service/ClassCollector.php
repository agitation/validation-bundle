<?php
/**
 * @package    agitation/common
 * @link       http://github.com/agitation/AgitCommonBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CommonBundle\Service;

use Symfony\Component\ClassLoader\ClassMapGenerator;

class ClassCollector extends FileCollector
{
    private $resolved = [];

    /**
     * @param string $location something like `FoobarBundle:Directory:Subdir`
     * @param bool $ignoreBrokenClasses ignore classes that are broken e.g. due to a missing parent class
     */
    public function collect($location, $ignoreBrokenClasses = false)
    {
        $files = parent::collect($location, 'php');
        $classes = [];

        foreach ($files as $file)
        {
            try
            {
                $className = $this->getFullClass($file);
                if (!$className) continue;

                // if broken classes should be ignored, we must suppress errors here,
                // because spl_autoload_register() throws errors instead of exceptions.

                $refl = $ignoreBrokenClasses
                    ? @ new \ReflectionClass($className)
                    : new \ReflectionClass($className);

                if ($refl->isAbstract()) continue;

                $classes[] = $className;
            }
            catch (\Exception $e)
            {
                if (!$ignoreBrokenClasses) throw $e;
            }
        }

        return $classes;
    }

    private function getFullClass($file)
    {
        $dir = dirname($file);

        if (!isset($this->resolved[$file]))
            $this->resolved += array_flip(ClassMapGenerator::createMap($dir));

        return isset($this->resolved[$file]) ? $this->resolved[$file] : '';
    }
}
