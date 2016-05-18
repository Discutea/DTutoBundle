<?php
namespace Discutea\DTutoBundle\Controller;

use Discutea\DTutoBundle\Controller\Base\BaseTutorialController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;


/**
 * TutorialController 
 * 
 * This class contains actions methods for forum.
 * This class extends BaseForumController.
 * 
 * @package  DTutoBundle
 * @author   David Verdier <contact@discutea.com>
 * @access   public
 */
class TutorialController extends BaseTutorialController
{

    /**
     *
     * @Route("/", name="discutea_tuto_homepage")
     * 
     * @return objet Symfony\Component\HttpFoundation\Response
     * 
     */
    public function indexAction()
    {

        return $this->render('DTutoBundle:index.html.twig', array(
          //  'categories' => $categories
        ));
    }
}
