<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Yaml\Parser;

/**
 * Lists dependencies of packages by reading use statements in PHP files and
 * service IDs from config files (currently only YAML).
 *
 * Not really elegant, but works for now.
 *
 * NOTE: Currently only works for full namespace declarations of classes --
 * no aliases, no mere namespaces, no relative class paths, no interfaces.
 */
class ShowPackageDependenciesCommand extends AbstractCommand
{
    private $ClassLoader;

    private $YamlParser;

    protected function configure()
    {
        $this
            ->setName('agit:util:dependencies')
            ->setDescription('Get dependencies of a bundle. The command will search all PHP files in the bundle for use statements and resolve the used packages’ IDs.')
            ->addArgument('bundle', InputArgument::REQUIRED, 'target bundle (e.g. FooBarBundle)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->flock(__FILE__)) return;

        if (!function_exists('token_get_all'))
            throw new \Exception('The Tokenizer extension is required for this command.');

        $output->write("Initializing … ");
        $FileCollector = $this->getContainer()->get('agit.core.filecollector');

        $bundle = $this->getContainer()->get('kernel')->getBundle($input->getArgument('bundle'));
        $bundleDir = $FileCollector->resolve($input->getArgument('bundle'));
        $vendorDir = realpath($this->getContainer()->getParameter('kernel.root_dir') . '/../vendor');

        $this->ClassLoader = require "$vendorDir/autoload.php";
        $this->YamlParser = new Parser();

        $packageName = is_readable("$bundleDir/composer.json")
            ? $this->getPackageNameFromComposerJson("$bundleDir/composer.json")
            : '';

        $output->writeln("done.");

        $output->write("Collecting PHP files … ");
        $phpFiles = $FileCollector->collect($bundleDir, 'php');
        $output->writeln(sprintf(" found %s files.", count($phpFiles)));

        $output->write("Collecting YAML files … ");
        $ymlFiles = $FileCollector->collect("$bundleDir/Resources/config", 'yml');
        $output->writeln(sprintf("found %s files.", count($ymlFiles)));

        $output->write("Collecting dependencies from PHP files … ");
        $dependenciesPhp = $this->collectDependencies($phpFiles);
        $output->writeln(sprintf("found %d dependencies.", count($dependenciesPhp)));

        $output->write("Collecting dependencies from YAML files … ");
        $dependenciesYml = $this->collectDependenciesFromYml($ymlFiles);
        $output->writeln(sprintf("found %d dependencies.", count($dependenciesYml)));

        $dependencies = array_merge($dependenciesPhp, $dependenciesYml);
        // dump $dependencies to see the actual classes

        $output->write("Finding composer.json files for dependencies … ");
        $composerFiles = $this->findComposerFiles($dependencies);
        $output->writeln(sprintf("done, found %d composer.json files.", count($composerFiles)));

        $output->write("Getting package names from composer.json files … ");
        $packageDependencies = $this->getPackageDependencies($composerFiles, $packageName);
        $output->writeln(sprintf("done, found %d packages.", count($packageDependencies)));

        $output->writeln("\nThe following package dependencies have been found:\n");

        $packageDependencies = array_map(function($packageDependency){
            return sprintf('        "%s" : "*"', $packageDependency);
        }, $packageDependencies);

        $output->writeln(implode(",\n", $packageDependencies));

        $output->writeln("\nYou will probably want to replace the asterisk with a suitable version requirement.");
        $output->writeln("\n<info>ATTENTION: This tool may fail to find certain dependencies, make sure to manually check dependencies, too.</info>");
    }

    private function collectDependencies($files)
    {
        $dependencies = [];

        foreach ($files as $filePath)
        {
            $deps = $this->getUsedClassesInFile($filePath);
            $dependencies = array_merge($dependencies, $deps);
        }

        $dependencies = array_values(array_unique($dependencies));
        sort($dependencies);

        return $dependencies;
    }

    private function collectDependenciesFromYml($files)
    {
        $references = [];

        foreach ($files as $file)
        {
            $yamlTree = $this->YamlParser->parse(file_get_contents($file));
            $references = array_merge($references, $this->findReferencesInYamlTree($yamlTree));
        }

        $references = array_unique($references);
        $classes = [];

        foreach ($references as $reference)
        {
            try
            {
                $instance = $this->getContainer()->get($reference);
                $classes[] = get_class($instance);
            }
            catch (\Exception $e) {}
        }

        return $classes;
    }


    private function findReferencesInYamlTree($tree)
    {
        $referencePatterns = [/*'factory_class', 'class',*/ 'arguments'];
        $found = [];

        foreach ($tree as $key => $value)
        {
            if (in_array($key, $referencePatterns) && is_array($value))
            {
                foreach ($value as $dep)
                    if (is_string($dep) && $dep[0] === '@')
                        $found[] = substr($dep, 1);
            }
            else if (is_array($value))
            {
                $found = array_merge($found, $this->findReferencesInYamlTree($value));
            }
        }

        return $found;
    }

    private function getUsedClassesInFile($path)
    {
        $tokens = token_get_all(file_get_contents($path));
        $k = 0;
        $classes = [];
        $doCollect = false;
        $currentClass = '';

        while (isset($tokens[$k]))
        {
            $isUse = false;
            $token = $value = null;

            if (is_array($tokens[$k]) && isset($tokens[$k][0]) && isset($tokens[$k][1]))
            {
                $token = $tokens[$k][0];
                $value = $tokens[$k][1];
            }
            elseif (is_string($tokens[$k]))
            {
                $token = $value = $tokens[$k];
            }

            if ($token === T_USE)
            {
                $isUse = true;
                $doCollect = true;
                $currentClass = '';
            }
            else if (!in_array($token, [T_WHITESPACE, T_NS_SEPARATOR, T_STRING]))
            {
                $doCollect = false;

                if ($currentClass)
                    $classes[] = preg_replace('|\s.*$|', '', $currentClass); // filter aliases (like "use XXX as YYY")
            }

            if (!$isUse && $doCollect && $value)
                $currentClass .= trim($value);

            ++$k;
        }

        return array_unique($classes);
    }

    private function findComposerFiles($classes)
    {
        $composerFiles = [];

        foreach ($classes as $class)
        {
            $path = $this->ClassLoader->findFile($class);

            if (!$path) continue;

            $composerFile = null;

            while (!$composerFile && strlen($path) > 1)
            {
                $path = dirname($path);

                if (is_readable("$path/composer.json"))
                    $composerFile = "$path/composer.json";
            }

            if (!$composerFile) continue;

            $composerFiles[] = $composerFile;
        }

        $composerFiles = array_values(array_unique($composerFiles));
        sort($composerFiles);

        return $composerFiles;
    }

    private function getPackageDependencies($composerFiles, $packageName)
    {
        $packageDependencies = [];

        foreach ($composerFiles as $composerFile)
        {
            $name = $this->getPackageNameFromComposerJson($composerFile);

            if ($name && $name !== $packageName)
                $packageDependencies[] = $name;
        }

        return array_values(array_unique($packageDependencies));
    }

    private function getPackageNameFromComposerJson($composerFile)
    {
        $name = null;

        $declaration = json_decode(file_get_contents($composerFile));

        if ($declaration && is_object($declaration) && isset($declaration->name) && is_string($declaration->name) && $declaration->name)
            $name = $declaration->name;

        return $name;
    }
}
