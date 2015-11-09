<?php
/**
 * @package    agitation/intl
 * @link       http://github.com/agitation/AgitIntlBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\IntlBundle\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Agit\CommonBundle\Exception\InternalErrorException;
use Agit\CommonBundle\Service\FileCollector;
use Agit\IntlBundle\Event\BundleFilesRegistrationEvent;
use Agit\IntlBundle\Event\BundleFilesCleanupEvent;
use Agit\IntlBundle\Event\CatalogRegistrationEvent;
use Agit\IntlBundle\Event\CatalogCleanupEvent;

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
    protected $gettextService;

    protected $fileCollector;

    protected $eventDispatcher;

    protected $locales;

    protected $fileTypes;

    protected $globalCatalogPath;

    protected $keywords = ['t', 'x:2c,1', 'n:1,2', 'ts:1', 'noop', 'noopN:1,2', 'noopX:2c,1'];

    // where we expect a bundle's translation files, relative to the bundle's base path
    protected $bundleCatalogSubdir;

    protected $catalogName = 'agit';

    protected $eventRegistrationTag = 'agit.intl';

    // lists of source files added through event listeners
    protected $sourceFileList = [];

    // lists of catalog files added through event listeners
    protected $catalogFileList = [];

    public function __construct(GettextService $gettextService, FileCollector $fileCollector, EventDispatcher $eventDispatcher, array $locales, array $fileTypes, $globalCatalogPath, $bundleCatalogSubdir)
    {
        $this->gettextService = $gettextService;
        $this->fileCollector = $fileCollector;
        $this->eventDispatcher = $eventDispatcher;
        $this->locales = $locales;
        $this->fileTypes = $fileTypes;
        $this->globalCatalogPath = $globalCatalogPath;
        $this->bundleCatalogSubdir = $bundleCatalogSubdir;
        $this->filesystem = new Filesystem();
    }

    /**
     * @param $bundleAlias a bundle alias
     */
    public function generateBundleCatalog($bundleAlias)
    {
        $bundlePath = $this->fileCollector->resolve($bundleAlias);

        if (!$bundlePath || !is_dir($bundlePath) || !is_readable($bundlePath))
            throw new InternalErrorException("Invalid bundle alias '$bundleAlias'.");

        $bundleCatalogPath = "$bundlePath{$this->bundleCatalogSubdir}";
        $fileList = [];
        $foundMessages = [];
        $counts = [];

        $this->eventDispatcher->dispatch(
            "{$this->eventRegistrationTag}.files.register",
            new BundleFilesRegistrationEvent($this, $bundleAlias));

        foreach ($this->fileTypes as $ext => $progLang)
        {
            $langFileList = [];
            $finder = (new Finder())->in($bundlePath)->notPath('/Test.*/')->notPath('/external/')->name("*\.$ext");

            foreach ($finder as $file)
            {
                $filePath = $file->getRealpath();
                $fileRelPath = str_replace($bundlePath, '', $filePath);
                $langFileList[$fileRelPath] = $filePath;
            }

            if (isset($this->sourceFileList[$progLang]))
                $langFileList += $this->sourceFileList[$progLang];

            $fileList += $langFileList;
            $foundMessages[] = $this->gettextService->xgettext($langFileList, $progLang, $this->keywords);
        }

        foreach ($this->locales as $locale)
        {
            $filename = "bundle.$locale.po";
            $filepath = "$bundleCatalogPath/$filename";
            $localeHeader = $this->gettextService->createCatalogHeader($locale);
            $localeFoundMessages = $localeHeader . implode("\n\n", $foundMessages);

            // filter all NEW messages
            $localeFoundMessages = $this->gettextService->msguniq($localeFoundMessages);

            $catalog = $this->filesystem->exists($filepath)
                ? file_get_contents($filepath)
                : $localeHeader;

            $catalog = $this->gettextService->msgmerge($catalog, $localeFoundMessages);

            $counts[$locale] = [
                'total' => $this->gettextService->countAllMessages($catalog),
                'translated' => $this->gettextService->countTranslatedMessages($catalog)
            ];

            $replacements = [];

            array_walk($fileList, function($path, $id) use ($bundleAlias, &$replacements) {
                $replacements["#: $path"] = "#: @$bundleAlias/$id";
            });

            $catalog = str_replace(array_keys($replacements), array_values($replacements), $catalog);

            $this->checkCatalogFileAndCreateIfNeccessary($filepath, $locale);
            $this->filesystem->dumpFile($filepath, $catalog);
        }

        // allow extensions to clean up
        $this->eventDispatcher->dispatch(
            "{$this->eventRegistrationTag}.files.cleanup",
            new BundleFilesCleanupEvent($bundleAlias));

        return $counts;
    }

    public function registerSourceFile($progLang, $fileId, $filePath)
    {
        if (!isset($this->sourceFileList[$progLang]))
            $this->sourceFileList[$progLang] = [];

        $this->sourceFileList[$progLang][$fileId] = $filePath;
    }

    /**
     * @param $bundleAliasList a list of bundle aliases
     */
    public function generateGlobalCatalog(array $bundleAliasList)
    {
        $catalogPath = "{$this->globalCatalogPath}/%s/LC_MESSAGES";
        $counts = [];

        $this->collectBundlePoFiles($bundleAliasList);

        $this->eventDispatcher->dispatch(
            "{$this->eventRegistrationTag}.catalog.register",
            new CatalogRegistrationEvent($this, $this->gettextService));

        foreach ($this->locales as $locale)
        {
            $locCatalogDirPath = sprintf($catalogPath, $locale);
            $locMachineFilePath = "$locCatalogDirPath/{$this->catalogName}.mo";

            $localeHeader = $this->gettextService->createCatalogHeader($locale);
            $currentCatalog = $localeHeader;
            $bundleTranslations = $localeHeader;

            if (isset($this->catalogFileList[$locale]))
                foreach ($this->catalogFileList[$locale] as $poFile)
                    $bundleTranslations .= $this->gettextService->removeHeaders(file_get_contents($poFile));

            $bundleTranslations = $this->gettextService->removeHeaders($bundleTranslations);
            $catalog = $this->gettextService->msguniq($currentCatalog, $bundleTranslations);
            $machineFormat = $this->gettextService->msgfmt($catalog, $stats);

            $counts[$locale] = [
                'total' => $this->gettextService->countAllMessages($catalog),
                'translated' => $stats[0]
            ];

            $this->checkDirectoryAndCreateIfNeccessary($locCatalogDirPath);
            $this->filesystem->dumpFile($locMachineFilePath, $machineFormat);
        }

        // allow extensions to clean up
        $this->eventDispatcher->dispatch(
            "{$this->eventRegistrationTag}.catalog.cleanup",
            new CatalogCleanupEvent());

        return $counts;
    }

    public function registerCatalogFile($locale, $filePath)
    {
        if (!isset($this->catalogFileList[$locale]))
            $this->catalogFileList[$locale] = [];

        $this->catalogFileList[$locale][] = $filePath;
    }

    protected function checkDirectoryAndCreateIfNeccessary($path)
    {
        clearstatcache(true);

        if (!$this->filesystem->exists($path))
            $this->filesystem->mkdir($path, 0755);
        elseif (!is_dir($path) || !is_writable($path))
            throw new InternalErrorException("The path '$path' is not a directory or not writable.");
    }

    protected function checkCatalogFileAndCreateIfNeccessary($path, $locale)
    {
        clearstatcache(true);

        if (!$this->filesystem->exists($path))
            $this->filesystem->dumpFile($path, $this->gettextService->createCatalogHeader($locale));
        elseif (!is_file($path) || !is_writable($path))
            throw new InternalErrorException("The file '$path' does not exist or is not writable.");
    }

    protected function collectBundlePoFiles($bundleAliasList)
    {
        foreach ($bundleAliasList as $path)
        {
            $bundlePath = $this->fileCollector->resolve($path);
            $bundleCatalogPath = "$bundlePath/{$this->bundleCatalogSubdir}";

            // we ignore bundles that don't "participate"
            if (!is_dir($bundleCatalogPath)) continue;

            $finder = (new Finder())->in($bundleCatalogPath)->name("*\.po");

            foreach ($finder as $file)
            {
                $filePath = $file->getRealpath();
                $locale = preg_replace('|^.*([a-z]{2}_[A-Z]{2}).po$|', '\1', $filePath);
                $this->registerCatalogFile($locale, $filePath);
            }
        }
    }
}
