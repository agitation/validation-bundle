<?php
/**
 * @package    agitation/intl
 * @link       http://github.com/agitation/AgitIntlBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\IntlBundle\Service;

use Agit\CoreBundle\Exception\InternalErrorException;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/*
    test:
        collect strings from PHP files
        collect strings from JS files
        merge catalogs, existing with new strings
        merge catalogs, new catalog with new strings
        merge catalogs with duplicate strings
        msgfmt and parse statistics
*/

class TranslationCatalogService
{
    protected $FileLocator;

    protected $locales;

    protected $fileTypes;

    protected $globalCatalogPath;

    // where we expect a bundle's translation files, relative to the bundle's base path
    protected $bundleCatalogSubdir;

    protected $catalogName = 'agit';

    public function __construct(GettextService $GettextService, FileLocator $FileLocator, array $locales, array $fileTypes, $globalCatalogPath, $bundleCatalogSubdir)
    {
        $this->GettextService = $GettextService;
        $this->FileLocator = $FileLocator;
        $this->locales = $locales;
        $this->fileTypes = $fileTypes;
        $this->globalCatalogPath = $globalCatalogPath;
        $this->bundleCatalogSubdir = $bundleCatalogSubdir;
        $this->Filesystem = new Filesystem();
    }

    /**
     * @param $bundlePath can be an absolute path or a bundle alias
     */
    public function generateBundleCatalog($bundlePath)
    {
        $bundlePath = $this->FileLocator->locate($bundlePath);
        $bundleCatalogPath = "$bundlePath/{$this->bundleCatalogSubdir}";

        $this->checkDirectoryAndCreateIfNeccessary($bundleCatalogPath);

        $keywords = ['t', 'x:2c,1', 'n:1,2', 'ts:1', 'noop'];
        $FinderList = [];

        foreach ($this->fileTypes as $ext => $progLang)
            $FinderList[$progLang] = (new Finder())->in($bundlePath)->notPath('/Test.*/')->name("*\.$ext");

        foreach ($this->locales as $locale)
        {
            $filename = "bundle.$locale.po";
            $filepath = "$bundleCatalogPath/$filename";

            $this->checkCatalogFileAndCreateIfNeccessary($filepath, $locale);

            $catalog = file_get_contents($filepath);
            $allCollectedMessages = '';

            foreach ($FinderList as $progLang => $Finder)
            {
                $filetypeMessages = $this->GettextService->xgettext($Finder, $progLang, $keywords, $locale);
                $allCollectedMessages = $this->GettextService->msguniq($allCollectedMessages, $filetypeMessages);
            }

            $catalog = $this->GettextService->msgmerge($catalog, $allCollectedMessages);
            $this->Filesystem->dumpFile($filepath, $catalog);
        }
    }

    /**
     * @param $bundlePathList a list of absolute paths or bundle aliases
     */
    public function generateGlobalCatalog(array $bundlePathList)
    {
        $poFiles = [];
        $catalogPath = "{$this->globalCatalogPath}/%s/LC_MESSAGES";

        foreach ($bundlePathList as $path)
        {
            $bundlePath = $this->FileLocator->locate($path);
            $bundleCatalogPath = "$bundlePath/{$this->bundleCatalogSubdir}";

            // we ignore bundles that don't "participate"
            if (!is_dir($bundleCatalogPath)) continue;

            $Finder = (new Finder())->in($bundleCatalogPath)->name("*\.po");

            foreach ($Finder as $File)
                $poFiles[] = $File->getRealpath();
        }

        foreach ($this->locales as $locale)
        {
            $locCatalogDirPath = sprintf($catalogPath, $locale);
            $locCatalogFilePath = "$locCatalogDirPath/{$this->catalogName}.po";
            $locMachineFilePath = "$locCatalogDirPath/{$this->catalogName}.mo";
            $allMessages = '';

            $this->checkDirectoryAndCreateIfNeccessary($locCatalogDirPath);
            $this->checkCatalogFileAndCreateIfNeccessary($locCatalogFilePath, $locale);

            foreach ($poFiles as $poFile)
                if (strpos($poFile, ".$locale.") !== false)
                    $allMessages = $this->GettextService->msguniq($allMessages, file_get_contents($poFile));

            $catalog = $this->GettextService->msgmerge(file_get_contents($locCatalogFilePath), $allMessages);
            $machine = $this->GettextService->msgfmt($catalog, $stats);

            $this->Filesystem->dumpFile($locCatalogFilePath, $catalog);
            $this->Filesystem->dumpFile($locMachineFilePath, $machine);
        }
    }

    protected function checkDirectoryAndCreateIfNeccessary($path)
    {
        clearstatcache(true);

        if (!$this->Filesystem->exists($path))
            $this->Filesystem->mkdir($path, 0744);
        elseif (!is_dir($path) || !is_writable($path))
            throw new InternalErrorException("The path '$path' is not a directory or not writable.");
    }

    protected function checkCatalogFileAndCreateIfNeccessary($path, $locale)
    {
        clearstatcache(true);

        if (!$this->Filesystem->exists($path))
            $this->Filesystem->dumpFile($path, $this->GettextService->createCatalogHeader($locale));
        elseif (!is_file($path) || !is_writable($path))
            throw new InternalErrorException("The file '$path' does not exist or is not writable.");
    }
}