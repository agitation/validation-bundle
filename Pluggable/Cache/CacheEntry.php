<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable\Cache;

use Agit\BaseBundle\Exception\InternalErrorException;

class CacheEntry
{
    private $id;

    private $data;

    public function setId($id)
    {
        if (! is_string($id)) {
            throw new InternalErrorException("The ID must be a string.");
        }

        $this->id = $id;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getData()
    {
        return $this->data;
    }
}
