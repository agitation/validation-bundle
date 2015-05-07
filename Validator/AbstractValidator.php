<?php
/**
 * @package    agitation/validation
 * @link       http://github.com/agitation/AgitValidationBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Validator;

use Agit\ValidationBundle\Exception\InvalidValueException;
use Agit\IntlBundle\Service\Translate;
use Agit\CoreBundle\Pluggable\Strategy\Object\PluginObjectInterface;

abstract class AbstractValidator implements PluginObjectInterface
{
    protected $translate;

    private $servicesList = [];

    private $validatorList = [];

    public function __construct()
    {
        $this->translate = new Translate();
    }

    /**
     * Validators can declare required services through the `Services`
     * annotation. The factory method of the validation service will inject the
     * services through `setService` method. Validators can access services
     * through `getService`.
     */
    final public function setService($id, $instance)
    {
        if (!is_object($instance))
            throw new InternalErrorException("Invalid service instance.");

        $this->servicesList[$id] = $instance;
    }

    final protected function getService($id)
    {
        if (!isset($this->servicesList[$id]) || !is_object($this->servicesList[$id]))
            throw new InternalErrorException("A service '$id' has not been injected.");

        return $this->servicesList[$id];
    }

    /**
     * Validators can also depend on other validators.
     */
    final public function setValidator($id, AbstractValidator $instance)
    {
        if (!is_object($instance))
            throw new InternalErrorException("Invalid validator instance.");

        $this->validatorList[$id] = $instance;
    }

    final protected function getValidator($id)
    {
        if (!isset($this->validatorList[$id]) || !is_object($this->validatorList[$id]))
            throw new InternalErrorException("A validator '$id' has not been injected.");

        return $this->validatorList[$id];
    }

    /**
     * override me
     */
    public function getId()
    {
        return lcfirst(substr(strrchr(get_class($this), '\\'), 1, -9));
    }

    /**
     * override me
     */
    public function getServiceDependencies()
    {
        return [];
    }

    /**
     * override me
     */
    public function getValidatorDependencies()
    {
        return [];
    }

    /**
     * The actual validator method. Will, in many cases, have additional parameters.
     */
    abstract public function validate($value);
}
