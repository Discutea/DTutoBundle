<?php

namespace Discutea\DTutoBundle\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;

class DForumExtension extends \Twig_Extension
{
    
    private $em;
    
    private $request;
    
    private $params;
    
    private $prefixUrl;
    
    public function __construct (EntityManager $em, RequestStack $request, array $params, $prefixUrl) {
        $this->em = $em;
        $this->request = $request->getCurrentRequest();
        $this->params = $params;
        $this->prefixUrl = $prefixUrl;
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
        $this->em->getRepository('DTutoBundle:Tutorial')->findAll();

    }

    public function tutoContribsNoValidate($locale = null)
    {

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
