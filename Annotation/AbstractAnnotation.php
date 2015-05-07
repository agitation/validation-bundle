<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Annotation;

use Agit\CoreBundle\Exception\InternalErrorException;

abstract class AbstractAnnotation implements \Serializable
{
    public function __construct(array $options = null)
    {
        $this->setOptions($options);
    }

    public function serialize()
    {
        return serialize($this->getOptions());
    }

    public function unserialize($options)
    {
        return $this->setOptions(unserialize($options));
    }

    public function setOptions(array $options = null)
    {
        if ($options && count($options))
            foreach ($options as $key => $value)
                $this->set($key, $value);
    }

    public function getOptions()
    {
        $options = [];

        foreach (get_object_vars($this) as $key => $value)
            if ($key[0] !== '_')
                $options[$key] = $value;

        return $options;
    }

    public function set($key, $value)
    {
        if (!property_exists($this, $key))
            throw new InternalErrorException(sprintf('Annotation property "%s" does not exist.', $key));

        if (strpos($key, '_') === 0)
            throw new InternalErrorException('Internal properties must not be modified via annotations.');

        $this->$key = $value;
    }

    public function get($key)
    {
        if ($key[0] === '_')
            throw new InternalErrorException('Internal properties must not be read through this method.');

        if (!property_exists($this, $key))
            throw new InternalErrorException(sprintf('Annotation property "%s" does not exist.', $key));

        return $this->$key;
    }
}
