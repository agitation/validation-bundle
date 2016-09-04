<?php

namespace Agit\BaseBundle\EventListener;

use Agit\BaseBundle\Service\FileCollector;
use Agit\BaseBundle\Event\TranslationFilesEvent;
use Symfony\Component\Filesystem\Filesystem;

class TranslationTwigListener
{
    protected $bundleTemplatesPath = "Resources/views";

    private $fileCollector;

    private $twig;

    public function __construct(FileCollector $fileCollector,\Twig_Environment $twig)
    {
        $this->fileCollector = $fileCollector;
        $this->twig = $twig;
    }

    public function onRegistration(TranslationFilesEvent $event)
    {
        $bundleAlias = $event->getBundleAlias();
        $tplDir = $this->fileCollector->resolve($bundleAlias);

        // storing the old values to reset them when we’re done
        $actualCachePath = $this->twig->getCache();
        $actualAutoReload = $this->twig->isAutoReload();

        // create tmp cache path
        $filesystem = new Filesystem();
        $cachePath = $event->getCacheBasePath() . md5(__CLASS__);
        $filesystem->mkdir($cachePath);

        // setting temporary values
        $this->twig->enableAutoReload();
        $this->twig->setCache($cachePath);

        foreach ($this->fileCollector->collect($tplDir, "twig") as $file)
        {
            $this->twig->loadTemplate($file); // force rendering
            $cacheFilePath = $this->twig->getCacheFilename($file);
            $fileId = str_replace($tplDir, "@$bundleAlias/", $file);
            $event->registerSourceFile($fileId, $cacheFilePath);
        }

        // resetting original values
        $this->twig->setCache($actualCachePath);
        call_user_func([$this->twig, $actualAutoReload ? "enableAutoReload" :  "disableAutoReload"]);

    }
}
