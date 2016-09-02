<?php
/**
 * @package    agitation/common
 * @link       http://github.com/agitation/AgitBaseBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Annotation;

use Agit\BaseBundle\Exception\InternalErrorException;

interface AnnotationInterface
{
    public function setOptions(array $options = null);

    public function getOptions();

    public function has($key);

    public function set($key, $value);

    public function get($key);
}
