<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\Strategy\Entity;

use Agit\CommonBundle\Annotation\AnnotationTrait;
use Agit\PluggableBundle\Strategy\PluginInterface;
use Agit\PluggableBundle\Strategy\Seed\SeedPluginInterface;
use Agit\PluggableBundle\Strategy\Seed\SeedEntry;

class EntitySeedPlugin implements SeedPluginInterface
{
    private $data = [];

    public function __construct(array $data, $pluginMeta)
    {
        foreach ($data as $entry)
        {
            $seedEntry = new SeedEntry();
            $seedEntry->setData($entry);
            $seedEntry->setDoUpdate($pluginMeta->get('update'));
            $this->data[] = $seedEntry;
        }
    }

    public function load()
    {
    }

    public function nextSeedEntry()
    {
        return array_pop($this->data);
    }
}
