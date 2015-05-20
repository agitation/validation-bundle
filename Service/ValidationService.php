<?php
/**
 * @package    agitation/validation
 * @link       http://github.com/agitation/AgitValidationBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Agit\CoreBundle\Exception\InternalErrorException;
use Agit\CoreBundle\Pluggable\Strategy\Object\ObjectLoader;
use Agit\IntlBundle\Service\Translate;
use Agit\ValidationBundle\Exception\InvalidValueException;

class ValidationService
{
    private $Container;

    private $ObjectLoader;

    private $Translate;

    private $ValidatorList = [];

    public function __construct(ObjectLoader $ObjectLoader, Translate $Translate, ContainerInterface $Container)
    {
        $this->ObjectLoader = $ObjectLoader;
        $this->Container = $Container;
        $this->ObjectLoader->setObjectFactory([$this, 'objectFactory']);
        $this->Translate = $Translate;
    }

    // shortcut
    public function validate($id, $value)
    {
        call_user_func_array(
            [$this->getValidator($id), 'validate'],
            array_slice(func_get_args(), 1));
    }

    // shortcut. extends the error message with a reference to the field.
    public function validateField($fieldName, $id, $value)
    {
        try {
            call_user_func_array(
                [$this->getValidator($id), 'validate'],
                array_slice(func_get_args(), 2));
        } catch(\Exception $e) {
            throw new InvalidValueException(sprintf($this->Translate->t("Invalid value for %s: %s"), $fieldName, $e->getMessage()));
        }
    }

    // shortcut. ATTENTION: instead of throwing an exception, it returns true/false
    public function isValid($id, $value)
    {
        $isValid = false;

        try
        {
            call_user_func_array([$this, 'validate'], func_get_args());
            $isValid = true;
        }
        catch (\Exception $e) { }

        return $isValid;
    }

    public function getValidator($id)
    {
        return $this->ObjectLoader->getObject($id);
    }

    public function objectFactory($id, $className)
    {
        $Validator = new $className();

        foreach ($Validator->getServiceDependencies() as $depName)
            $Validator->setService($depName, $this->Container->get($depName));

        foreach ($Validator->getValidatorDependencies() as $depName)
            $Validator->setValidator($depName, $this->getValidator($depName));

        return $Validator;
    }
}
