<?php

namespace Discutea\DTutoBundle\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;

class DTutoExtension extends \Twig_Extension
{
    
    private $em;
    
    private $request;
    
    public function __construct (EntityManager $em, RequestStack $request) {
        $this->em = $em;
        $this->request = $request->getCurrentRequest();
    }
    
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('tutoNoValidate', array($this, 'tutoNoValidate')),
            new \Twig_SimpleFunction('tutoContribsNoValidate', array($this, 'tutoContribsNoValidate')),
            new \Twig_SimpleFunction('lastTutoContribs', array($this, 'lastTutoContribs')),
            new \Twig_SimpleFunction('lastTutorials', array($this, 'lastTutorials')),
        );
    }

    public function tutoNoValidate($locale = null)
    {
        $tutorials = $this->em->getRepository('DTutoBundle:Tutorial')->findAll();

        return $tutorials;
    }

    public function tutoContribsNoValidate($locale = null)
    {
        if($locale === NULL) {
             
        }
    }

    public function lastTutoContribs($locale = null)
    {

    }

    public function lastTutorials($locale = null)
    {

    }

    public function getName()
    {
        return 'DTutoBundle.twig.extension';
    }
}
