<?php
/**
 * @package    agitation/intl
 * @link       http://github.com/agitation/AgitIntlBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\IntlBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Agit\CommonBundle\Command\SingletonCommandTrait;
use Gettext\Translations;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Agit\IntlBundle\Event\BundleFilesRegistrationEvent;
use Agit\IntlBundle\Event\CatalogRegistrationEvent;
use Agit\IntlBundle\Event\CleanupEvent;


class CreateCatalogsCommand extends ContainerAwareCommand
{
    use SingletonCommandTrait;

    private $eventRegistrationTag = "agit.intl";

    private $catalogSubdir = "Resources/translations";

    private $frontendSubdir = "Resources/public/js/var";

    private $extractorOptions = ["functions" => [
        "t" => "gettext", "ts" => "gettext", "tl" => "gettext", "noop" => "gettext",
        "x" => "pgettext", "xl" => "pgettext", "noopX" => "pgettext",
        "n" => "ngettext", "nl" => "ngettext", "noopN" => "ngettext"
    ]];

    private $extraSourceFiles = [];

    private $extraCatalogFiles = [];

    protected function configure()
    {
        $this
            ->setName("agit:intl:catalogs")
            ->setDescription("Extract translatable strings in a bundle’s into .po and .json files, then add/merge them to the global catalogs.")
            ->addArgument("bundle", InputArgument::REQUIRED, "bundle alias, e.g. AcmeFoobarBundle");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->flock(__FILE__)) return;

        $container = $this->getContainer();
        $filesystem = new Filesystem();
        $bundleAlias = $input->getArgument("bundle");
        $bundlePath = $container->get("agit.common.filecollector")->resolve($bundleAlias);
        $globalCatalogPath = $container->getParameter("kernel.root_dir") . "/$this->catalogSubdir";
        $defaultLocale = $container->get("agit.intl.locale")->getDefaultLocale();

        // collect files

        $finder = (new Finder())->in("$bundlePath")
            ->name("*\.php")
            ->name("*\.js")
            ->notPath("/test.*/i")
            ->notPath("public/js/ext");



        $files = [];

        foreach ($finder as $file)
        {
            $filePath = $file->getRealpath();
            $alias = str_replace($bundlePath, "@$bundleAlias/", $filePath);
            $files[$alias] = $filePath;
        }

        $this->dispatchRegistrationEvents($bundleAlias);
        $files += $this->extraSourceFiles;

        $frontendFiles = array_filter($files, function($file){ return preg_match("|\.js$|", $file); });
        $backendFiles = array_filter($files, function($file){ return !preg_match("|\.js$|", $file); });

        $frontendCatalogs = "";

        foreach ($container->getParameter("agit.intl.locales") as $locale)
        {
            $globalCatalogFile = "$globalCatalogPath/$locale/LC_MESSAGES/agit.po";
            $globalCatalogMoFile = "$globalCatalogPath/$locale/LC_MESSAGES/agit.mo";
            $globalCatalog = $filesystem->exists($globalCatalogFile)
                ? Translations::fromPoFile($globalCatalogFile)
                : new Translations();

            $bundleCatalogFile = "$bundlePath/$this->catalogSubdir/bundle.$locale.po";
            $oldBundleCatalog = ($filesystem->exists($bundleCatalogFile))
                ? Translations::fromPoFile($bundleCatalogFile)
                : new Translations();

            // NOTE: we delete all headers and only set language in order to avoid garbage commits
            $bundleCatalog = new Translations();
            $bundleCatalog->deleteHeaders();
            $bundleCatalog->setLanguage($locale);

            // first: only JS messages

            foreach ($frontendFiles as $file)
                $bundleCatalog->addFromJsCodeFile($file, $this->extractorOptions);

            $bundleCatalog->mergeWith($oldBundleCatalog, 0);
            $bundleCatalog->mergeWith($globalCatalog, 0);

            if ($bundleCatalog->count() && $locale !== $defaultLocale)
            {
                $transMap = [];

                foreach ($bundleCatalog as $entry)
                {
                    $msgid = ltrim($entry->getId(), "\004");
                    $msgstr = $entry->getTranslation();

                    $transMap[$msgid] = $entry->hasPlural()
                        ? array_merge([$msgstr], $entry->getPluralTranslations())
                        : $msgstr;
                }

                $frontendCatalogs .= sprintf("ag.intl.register(\"%s\", %s);\n\n",
                    $locale,
                    json_encode($transMap, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                );
            }

            // now the same with all messages

            foreach ($backendFiles as $file)
                $bundleCatalog->addFromPhpCodeFile($file, $this->extractorOptions);

            $bundleCatalog->mergeWith($oldBundleCatalog, 0);
            $bundleCatalog->mergeWith($globalCatalog, 0);

            $catalog = $bundleCatalog->toPoString();
            $catalog = str_replace(array_values($files), array_keys($files), $catalog);

            if ($bundleCatalog->count())
                $filesystem->dumpFile("$bundlePath/$this->catalogSubdir/bundle.$locale.po", $catalog);

            // finally, create the global catalog

            $globalCatalog->addFromPoString($catalog);

            if (isset($this->extraCatalogFiles[$locale]))
                foreach ($this->extraCatalogFiles[$locale] as $extraCatalogFile)
                    $globalCatalog->addFromPoFile($extraCatalogFile);

            // NOTE: we delete all headers and only set language in order to avoid garbage commits
            $globalCatalog->deleteHeaders();
            $globalCatalog->setLanguage($locale);
            $globalCatalog->toPoFile($globalCatalogFile);
            $globalCatalog->toMoFile($globalCatalogMoFile);
        }

        if ($frontendCatalogs)
            $filesystem->dumpFile("$bundlePath/$this->frontendSubdir/translations.js", $frontendCatalogs);

        $this->dispatchCleanupEvents($bundleAlias);
    }

    public function registerSourceFile($alias, $path)
    {
        $this->extraSourceFiles[$alias] = $path;
    }

    public function registerCatalogFile($locale, $path)
    {
        if (!isset($this->extraCatalogFiles[$locale]))
            $this->extraCatalogFiles[$locale] = [];

        $this->extraCatalogFiles[$locale][] = $path;
    }

    private function dispatchRegistrationEvents($bundleAlias)
    {
        $eventDispatcher = $this->getContainer()->get("event_dispatcher");

        $eventDispatcher->dispatch(
            "{$this->eventRegistrationTag}.files.register",
            new BundleFilesRegistrationEvent($this, $bundleAlias));

        $eventDispatcher->dispatch(
            "{$this->eventRegistrationTag}.catalog.register",
            new CatalogRegistrationEvent($this));
    }

    private function dispatchCleanupEvents($bundleAlias)
    {
        $eventDispatcher = $this->getContainer()->get("event_dispatcher");

        $eventDispatcher->dispatch(
            "{$this->eventRegistrationTag}.files.cleanup",
            new CleanupEvent($this, $bundleAlias));

        $eventDispatcher->dispatch(
            "{$this->eventRegistrationTag}.catalog.cleanup",
            new CleanupEvent($this, $bundleAlias));
    }
}
