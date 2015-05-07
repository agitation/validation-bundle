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

/**
 * This class provides wrappers around various gettext shell functions.
 * Note that they return file dumps (strings) rather than file handles.
 */
class GettextService
{
    // the location of our header template file
    protected $headerFile;

    // the location of our plural definitions file
    protected $pluralsFile;

    public function __construct($headerFile, $pluralsFile)
    {
        $this->headerFile = realpath(__DIR__."/../$headerFile");
        $this->pluralsFile = realpath(__DIR__."/../$pluralsFile");
        $this->Filesystem = new Filesystem();
    }

    /**
     * Extracts translatable strings from source files
     *
     * @param $Finder instance of finder which contains the files to parse
     * @param $progLang programming language, as understood by `xgettext`
     * @param $keywords xgettext translation function keywords
     */
    public function xgettext(Finder $Finder, $progLang, array $keywords)
    {
        $fileListFile = $this->makeTmpFileName(__FUNCTION__);
        $messagesFile = $this->makeTmpFileName(__FUNCTION__);
        $fileList = [];

        foreach ($Finder as $File)
            $fileList[] = $File->getRealpath();

        $this->Filesystem->dumpFile($fileListFile, implode("\n", $fileList));
        $this->Filesystem->dumpFile($messagesFile, '');

        $command = sprintf(
            "xgettext --language=%s --from-code=utf-8 -j -f %s -o %s",
            escapeshellarg($progLang),
            escapeshellarg($fileListFile),
            escapeshellarg($messagesFile));

        foreach ($keywords as $keyword)
            $command .= sprintf(" --keyword=%s", escapeshellarg($keyword));

        exec($command, $output, $ret);

        $messages = file_get_contents($messagesFile);
        $this->Filesystem->remove($messagesFile);
        $this->Filesystem->remove($fileListFile);

        if ($ret) throw new InternalErrorException(sprintf("Error code $ret from msgattrib: %s\n", implode("\n", $output)));

        return $messages;
    }

    /**
     * Creates a new catalog from existing translations ($catalog) and new
     * found strings ($newMessages).
     *
     * IMPORTANT: msgmerge will mark all messages "obsolete" which are not found
     * in the new messages. This means, you must not try to merge "partial" sets
     * of new messages into the catalog, or the catalog will be incomplete.
     *
     * @param $catalog The existing catalog
     * @param $newMessages The new translatable strings
     */
    public function msgmerge($catalog, $newMessages)
    {
        $catalogFile = $this->makeTmpFileName(__FUNCTION__);
        $newMessagesFile = $this->makeTmpFileName(__FUNCTION__);
        $this->Filesystem->dumpFile($catalogFile, $catalog);
        $this->Filesystem->dumpFile($newMessagesFile, $newMessages);

        $command1 = sprintf('msgmerge -q -F -N --no-wrap -o %1$s %1$s %2$s', escapeshellarg($catalogFile), escapeshellarg($newMessagesFile));
        exec($command1, $output1, $ret1);

        // remove obsolete translations
        $command2 = sprintf('msgattrib --no-fuzzy --no-wrap --no-obsolete -o %1$s %1$s', escapeshellarg($catalogFile));
        exec($command2, $output2, $ret2);

        $newCatalog = file_get_contents($catalogFile);
        $this->Filesystem->remove($catalogFile);
        $this->Filesystem->remove($newMessagesFile);

        if ($ret1) throw new InternalErrorException(sprintf("Error code $ret1 from msgattrib: %s\n", implode("\n", $output1)));
        if ($ret2) throw new InternalErrorException(sprintf("Error code $ret2 from msgattrib: %s\n", implode("\n", $output2)));

        return $newCatalog;
    }

    /**
     * Joins two message sets. If duplicate messages are found, the first one is
     * used. NOTE: Do not pass catalogs with a header (i.e. empty msgid). They
     * will cause exceptions.
     *
     * @param $catalog1 first catalog
     * @param $catalog2 second catalog
     */
    public function msguniq($catalog1, $catalog2)
    {
        $catalogFile = $this->makeTmpFileName(__FUNCTION__);
        $catalog = "$catalog1\n\n$catalog2";
        $this->Filesystem->dumpFile($catalogFile, $catalog);

        $command = sprintf('msguniq --use-first --no-wrap -o %1$s %1$s', escapeshellarg($catalogFile));
        exec($command, $output, $ret);

        $newCatalog = file_get_contents($catalogFile);
        $this->Filesystem->remove($catalogFile);

        if ($ret) throw new InternalErrorException(sprintf("Error code $ret from msguniq: %s\n", implode("\n", $output)));

        return $newCatalog;
    }

    /**
     * Creates the machine readable message catalog. NOTE: Expects and returns
     * strings, not file handles.
     *
     * @param $catalog catalog to convert
     */
    public function msgfmt($catalog, &$stats=null)
    {
        $catalogFile = $this->makeTmpFileName(__FUNCTION__);
        $machineFile = $this->makeTmpFileName(__FUNCTION__);
        $this->Filesystem->dumpFile($catalogFile, $catalog);

        $command = sprintf('msgfmt --statistics --check-format -vo %s %s 2>&1', escapeshellarg($machineFile), escapeshellarg($catalogFile));
        $out = exec($command, $output, $ret);

        $machineCatalog = file_get_contents($machineFile);
        $this->Filesystem->remove($catalogFile);
        $this->Filesystem->remove($machineFile);

        if ($ret) throw new InternalErrorException(sprintf("Error code $ret from msgfmt: %s\n", implode("\n", $output)));

        // collect statistics
        $v1 = (int)preg_replace("|^.*(\d+)\s*[^n]translated.*$|U", '\1', $out);
        $v2 = (int)preg_replace("|^.*(\d+)\s*untranslated.*$|U", '\1', $out);
        $stats = [$v1, $v1 + $v2];

        return $machineCatalog;
    }


    public function createCatalogHeader($locale)
    {
        $header = trim(file_get_contents($this->headerFile));

        $locKey = str_replace('_', '-', strtolower($locale));
        $langKey = substr($locKey, 0, 2);
        $plurals = json_decode(file_get_contents($this->pluralsFile));
        $plural = $plurals->en; // default

        if (isset($plurals->$locKey))
            $plural = $plurals->$locKey;
        elseif (isset($plurals->$langKey))
            $plural = $plurals->$langKey;

        $header .= "\n\"$plural\\n\"\n";

        return $header;
    }

    protected function makeTmpFileName($methodName)
    {
        return tempnam(sys_get_temp_dir(), "agit.intl.$methodName.");
    }
}