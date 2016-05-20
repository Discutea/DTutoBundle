<?php
namespace Discutea\DTutoBundle\Twig;

use Doctrine\ORM\EntityManager;
use Discutea\DTutoBundle\Entity\Tutorial;

class DTutoExtension extends \Twig_Extension
{
    
    private $em;
    private $twig;
    
    public function __construct (EntityManager $em, \Twig_Environment $twig) {
        $this->em = $em;
        $this->twig = $twig;
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

    public function getTutoContribs(Tutorial $tutorial)
    {
        return $this->twig->render('DTutoBundle:switcher.html.twig', array(
            'contribs' => $tutorial->getContributions(),
            'current'  => $tutorial->getCurrent()
        ));
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
