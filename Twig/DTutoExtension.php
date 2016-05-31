<?php
namespace Discutea\DTutoBundle\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\User\UserInterface as User;

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
            new \Twig_SimpleFunction('dtContribsByUser', array($this, 'dtContribsByUser')),
            new \Twig_SimpleFunction('dtContribsInProgress', array($this, 'dtContribsInProgress')),
            new \Twig_SimpleFunction('dtContribsRejected', array($this, 'dtContribsRejected')),
            new \Twig_SimpleFunction('dtContribsSubmitted', array($this, 'dtContribsSubmitted')),
            new \Twig_SimpleFunction('dtContribsValidate', array($this, 'dtContribsValidate')),
        );
    }

    public function dtContribsByUser(User $user, $limit = null)
    {
        $contribs = $this->em->getRepository('DTutoBundle:Contribution')->findBy(
            array('contributor' => $user),
            array('date' => 'DESC'),
            $limit,
            null);

        return $contribs;
    }

    public function dtContribsInProgress($limit = null)
    {
        $contribs = $this->em->getRepository('DTutoBundle:Contribution')->findBy(
            array('status' => 0),
            array('date' => 'DESC'),
            $limit,
            null);

        return $contribs;
    }

    public function dtContribsRejected($limit = null)
    {
        $contribs = $this->em->getRepository('DTutoBundle:Contribution')->findBy(
            array('status' => 1),
            array('date' => 'DESC'),
            $limit,
            null);

        return $contribs;
    }
    
    public function dtContribsSubmitted($limit = null)
    {
        $contribs = $this->em->getRepository('DTutoBundle:Contribution')->findBy(
            array('status' => 2),
            array('date' => 'DESC'),
            $limit,
            null);

        return $contribs;
    }

    public function dtContribsValidate($limit = null)
    {
        $contribs = $this->em->getRepository('DTutoBundle:Contribution')->findBy(
            array('status' => 3),
            array('date' => 'DESC'),
            $limit,
            null);

        return $contribs;
    }
    
    public function getName()
    {
        return 'DTutoBundle.twig.extension';
    }
}
