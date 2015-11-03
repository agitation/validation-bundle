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
use Agit\PluggableBundle\Strategy\Object\ObjectLoaderFactory;
use Agit\IntlBundle\Service\Translate;
use Agit\ValidationBundle\Exception\InvalidValueException;

class ValidationService
{
    private $objectLoader;

    private $translate;

    private $validatorList = [];

    public function __construct(ObjectLoaderFactory $objectLoaderFactory, Translate $translate)
    {
        $this->objectLoader = $objectLoaderFactory->create("agit.validation");
        $this->translate = $translate;
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
            throw new InvalidValueException(sprintf($this->translate->t("Invalid value for %s: %s"), $fieldName, $e->getMessage()));
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
        if (!isset($validatorList[$id]))
        {
            $validatorList[$id] = $this->objectLoader->getObject($id);
            $validatorList[$id]->setValidationService($this);
        }

        return $validatorList[$id];
    }
}
