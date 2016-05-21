<?php
namespace Discutea\DTutoBundle\Twig;

use Doctrine\ORM\EntityManager;

/**
 * 
 * @package  DTutoBundle
 * @author   David Verdier <contact@discutea.com>
 * https://www.linkedin.com/in/verdierdavid
 *
 */
class DTutoExtension extends \Twig_Extension
{
    
    private $em;
    
    public function __construct (EntityManager $em) {
        $this->em = $em;
    }
    
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getTutoContribs', array($this, 'getTutoContribs', array('needs_environment' => true))),
            new \Twig_SimpleFunction('tutoNoValidate', array($this, 'tutoNoValidate')),
            new \Twig_SimpleFunction('tutoContribsNoValidate', array($this, 'tutoContribsNoValidate')),
            new \Twig_SimpleFunction('lastTutoContribs', array($this, 'lastTutoContribs')),
            new \Twig_SimpleFunction('lastTutorials', array($this, 'lastTutorials')),
        );
    }

    public function tutoNoValidate()
    {
        $tutorials = $this->em->getRepository('DTutoBundle:Tutorial')->findAll();

        return $tutorials;
    }

    public function tutoContribsNoValidate()
    {

    }

    public function lastTutoContribs()
    {

    }

    public function lastTutorials()
    {

    }

    public function getName()
    {
        return 'DTutoBundle.twig.extension';
    }
}
