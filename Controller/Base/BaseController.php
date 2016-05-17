<?php
namespace Discutea\DTutoBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * BaseController is a sample class for use recurring variables in controllers.
 *
 * @package  DTutoBundle
 * @author   David Verdier <contact@discutea.com>
 * @access   protected
 */
class BaseController extends Controller
{

    /*
     * @var object $em Doctrine\ORM\EntityManager
     */
    protected $em;

    /*
     * @var object $authorizationChecker Symfony\Component\Security\Core\Authorization\AuthorizationChecker
     */
    protected $authorizationChecker;

    /*
     * @var object $translator Symfony\Component\Translation\DataCollectorTranslator
     */
    protected $translator;

    /*
     * @return object Doctrine\ORM\EntityManager
     */
    protected function getEm() {
        if  ( $this->em === NULL ) {
            $this->em = $this->getDoctrine()->getManager();
        }
        
        return $this->em;
    }

    /*
     * 
     * @return object Symfony\Component\Security\Core\Authorization\AuthorizationChecker
     */
    protected function getAuthorization() {
        if  ( $this->authorizationChecker === NULL ) {
            $this->authorizationChecker = $this->get('security.authorization_checker');
        }
        
        return $this->authorizationChecker;
    }

    /**
     * 
     * @return object Symfony\Component\Translation\DataCollectorTranslator
     */
    protected function getTranslator() {
        if  ( $this->translator === NULL ) {
            $this->translator = $this->get('translator');
        }
        
        return $this->translator;
    }
}
