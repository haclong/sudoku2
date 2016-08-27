<?php

namespace AppBundle\Utils;

/**
 * Description of TwigExtension
 *
 * @author haclong
 */
class TwigExtension extends \Twig_Extension {
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('sqrt', array($this, 'sqrtFilter')),
        );
    }

    public function sqrtFilter($number)
    {
        return sqrt($number);
    }

    public function getName()
    {
        return 'app_extension';
    }
}