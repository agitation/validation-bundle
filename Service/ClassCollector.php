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
     */
    public function collect($location, $extension = 'php')
    {
        $files = parent::collect($location, $extension);
        $classes = [];

        foreach ($files as $file)
        {
            $className = $this->getFullClass($file);
            if (!$className) continue;

            $refl = new \ReflectionClass($className);
            if ($refl->isAbstract()) continue;

            $classes[] = $className;
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
