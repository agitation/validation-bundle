<?php
/**
 * @package    agitation/intl
 * @link       http://github.com/agitation/AgitIntlBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\IntlBundle\Service;

use Agit\CommonBundle\Exception\InternalErrorException;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Symfony\Component\Filesystem\Filesystem;

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
        $this->filesystem = new Filesystem();
    }

    /**
     * Extracts translatable strings from source files
     *
     * @param $fileList list of files to parse
     * @param $progLang programming language, as understood by `xgettext`
     * @param $keywords xgettext translation function keywords
     */
    public function xgettext(array $fileList, $progLang, array $keywords)
    {
        $fileListFile = $this->makeTmpFileName(__FUNCTION__);
        $messagesFile = $this->makeTmpFileName(__FUNCTION__);

        $this->filesystem->dumpFile($fileListFile, implode("\n", $fileList));
        $this->filesystem->dumpFile($messagesFile, '');

        $command = sprintf(
            // we cannot use --omit-header because then the --from-code wouldn't work
            "xgettext --language=%s --from-code=UTF-8 --no-wrap -j -f %s -o %s",
            escapeshellarg($progLang),
            escapeshellarg($fileListFile),
            escapeshellarg($messagesFile));

        foreach ($keywords as $keyword)
            $command .= sprintf(" --keyword=%s", escapeshellarg($keyword));

        exec($command, $output, $ret);

        if ($ret) throw new InternalErrorException(sprintf("Error code $ret from msgattrib: %s\n", implode("\n", $output)));

        $messages = file_get_contents($messagesFile);

        // remove headers, they cause trouble with other steps
        $messages = $this->removeHeaders($messages);

        $this->filesystem->remove($messagesFile);
        $this->filesystem->remove($fileListFile);

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
        $this->filesystem->dumpFile($catalogFile, $catalog);
        $this->filesystem->dumpFile($newMessagesFile, $newMessages);

        $command1 = sprintf('msgmerge -q -F -N --no-wrap -o %1$s %1$s %2$s', escapeshellarg($catalogFile), escapeshellarg($newMessagesFile));
        exec($command1, $output1, $ret1);

        if ($ret1) throw new InternalErrorException(sprintf("1: Error code $ret1 from msgmerge: %s\n", implode("\n", $output1)));

        //remove obsolete translations
        $command2 = sprintf('msgattrib --no-fuzzy --no-wrap --no-obsolete -o %1$s %2$s', escapeshellarg($catalogFile), escapeshellarg($catalogFile));
        exec($command2, $output2, $ret2);

        $newCatalog = file_get_contents($catalogFile);

        $this->filesystem->remove($catalogFile);
        $this->filesystem->remove($newMessagesFile);

        if ($ret2) throw new InternalErrorException(sprintf("2: Error code $ret2 from msgattrib: %s\n", implode("\n", $output2)));

        return $newCatalog;
    }

    /**
     * Joins multiple message sets. If duplicate messages are found, the first one is
     * used. NOTE: Do not pass catalogs with a header (i.e. empty msgid). They
     * will cause exceptions.
     */
    public function msguniq($catalog/*, ...*/)
    {
        $catalog = implode("\n\n", func_get_args());
        $newCatalog = '';

        if (trim($catalog))
        {
            $catalogFile = $this->makeTmpFileName(__FUNCTION__);
            $this->filesystem->dumpFile($catalogFile, $catalog);

            $command = sprintf('msguniq --use-first --to-code=UTF-8 --no-wrap -o %1$s %1$s', escapeshellarg($catalogFile));
            exec($command, $output, $ret);

            $newCatalog = file_get_contents($catalogFile);
            $this->filesystem->remove($catalogFile);

            if ($ret) throw new InternalErrorException(sprintf("Error code $ret from msguniq: %s\n", implode("\n", $output)));
        }

        return $newCatalog;
    }

    /**
     * Creates the machine readable message catalog. NOTE: Expects and returns
     * strings, not file handles.
     *
     * @param $catalog catalog to convert
     */
    public function msgfmt($catalog, &$stats = null)
    {
        // remove all comments. this will especially remove tags like
        // "php-format"; they serve no purpose and only cause trouble
        $catalog = preg_replace('|^#.*$|m', '', $catalog);

        $catalogFile = $this->makeTmpFileName(__FUNCTION__);
        $machineFile = $this->makeTmpFileName(__FUNCTION__);
        $this->filesystem->dumpFile($catalogFile, $catalog);

        $command = sprintf('msgfmt --statistics --check-format -vo %s %s 2>&1', escapeshellarg($machineFile), escapeshellarg($catalogFile));
        $out = exec($command, $output, $ret);

        $machineCatalog = file_get_contents($machineFile);
        $this->filesystem->remove($catalogFile);
        $this->filesystem->remove($machineFile);

        if ($ret) throw new InternalErrorException(sprintf("Error code $ret from msgfmt: %s\n", implode("\n", $output)));

        // collect statistics
        $v1 = (int)preg_replace("|^.*(\d+)\s*[^n]translated.*$|U", '\1', $out);
        $v2 = (int)preg_replace("|^.*(\d+)\s*untranslated.*$|U", '\1', $out);
        $stats = [$v1, $v1 + $v2];

        return $machineCatalog;
    }

    public function countAllMessages($catalog)
    {
        $lines = preg_split("|[\r\n]+|", $catalog);

        $lines = array_filter($lines, function($line) {
            return strpos($line, 'msgid ') === 0 && !preg_match('|msgid\s+""|', $line);
        });

        return count($lines);
    }

    public function countTranslatedMessages($catalog)
    {
        $lines = preg_split("|[\r\n]+|", $catalog);

        $lines = array_filter($lines, function($line) {
            return preg_match('|^msgstr(\[0\])?\s+"|', $line) && !preg_match('|msgstr\s+""|', $line);
        });

        return count($lines);
    }

    // ATTENTION: This works only properly when the catalog was formatted with --no-wrap
    public function removeHeaders($catalog)
    {
        $catalog = preg_replace('|^#\s.*$|m', '', $catalog); // comments
        $catalog = preg_replace("|msgid\s+\"\"\nmsgstr\s+\"\"|", '', $catalog);  // header definitions
        $catalog = preg_replace('|^".*"$|m', '', $catalog); // header lines

        return trim($catalog);
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
