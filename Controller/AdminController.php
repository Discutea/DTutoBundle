<?php
namespace Discutea\DTutoBundle\Controller;

use Discutea\DTutoBundle\Controller\Base\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * AdminController 
 * 
 * @package  DTutoBundle
 * @author   David Verdier <contact@discutea.com>
 * https://www.linkedin.com/in/verdierdavid
 *
 */
class AdminController extends BaseController
{

    /**
     * 
     * Admin's dashboard
     * 
     * @Route("/admin", name="discutea_tuto_admin_dashboard")
     * @Security("is_granted('ROLE_MODERATOR')")
     * 
     */
    public function indexAction()
    {
        $em = $this->getEm();

        if ($this->getAuthorization()->isGranted('ROLE_ADMIN')) {
            $categories = $em->getRepository('DTutoBundle:Category')->findAll();
        } else {
            $categories = NULL;
        }

        return $this->render('DTutoBundle::Admin/index.html.twig', array(
            'tutorials' => $em->getRepository('DTutoBundle:Tutorial')->findAll(),
            'categories' => $categories
        ));
    }
}
