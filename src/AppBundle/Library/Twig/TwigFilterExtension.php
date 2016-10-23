<?php

namespace AppBundle\Library\Twig;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use \Twig_Extension;

/**
 * TwigFilterExtension
 */
class TwigFilterExtension extends Twig_Extension
{
    /** 
     * 
     * @var Translator  
     */
    protected $translator;
    
    /**
     * Resolve dependencies
     * 
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }  
    
    /**
     * 
     * @return type
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter(
                'icon', 
                array($this, 'icon'), 
                array('is_safe' => array('html'))
                ),
            );
    }
    
    /**
     * Create Bootstrap icon
     * 
     * @param type $icon
     * @return type
     */
    public function icon($icon, $extraClass = '')
    {
        return sprintf(
            '<span class="%s %s"></span>', 
            $this->translator->trans($icon), 
            $extraClass
            );
    }
    
    /**
     * 
     * @return string
     */
    public function getName()
    {
        return 'app.twig.filter.service';
    }     
}