<?php
/**
 * @package    agitation/intl
 * @link       http://github.com/agitation/AgitIntlBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\IntlBundle\Twig;

class TranslationExtension extends \Twig_Extension
{
    private $Translate;

    public function __construct($Translate)
    {
        $this->Translate = $Translate;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            't'  => new \Twig_Function_Method($this, 't',  ['is_safe' => ['all']]),
            'n'  => new \Twig_Function_Method($this, 'n',  ['is_safe' => ['all']]),
            'x'  => new \Twig_Function_Method($this, 'x',  ['is_safe' => ['all']]),
            'u'  => new \Twig_Function_Method($this, 'u',  ['is_safe' => ['all']]),
            'ts' => new \Twig_Function_Method($this, 'ts', ['is_safe' => ['all']])
        ];
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'translation';
    }

    public function t($string)
    {
        return $this->Translate->t($string);
    }

    public function n($string1, $string2, $num)
    {
        return $this->Translate->n($string1, $string2, $num);
    }

    public function x($string, $ctx)
    {
        return $this->Translate->x($string, $ctx);
    }

    public function u($string, $locale)
    {
        return $this->Translate->u($string, $locale);
    }

    public function ts($string)
    {
        $args = array_slice(func_get_args(), 2);
        $translated = $this->t($string);
        return vsprintf($translated, $args);
    }
}
