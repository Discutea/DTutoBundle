<?php
namespace Discutea\DTutoBundle\Controller;

use Discutea\DTutoBundle\Controller\Base\BaseTutorialController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Discutea\DTutoBundle\Entity\Tutorial;
use Discutea\DTutoBundle\Entity\Category;
use Discutea\DTutoBundle\Entity\Contribution;
use Discutea\DTutoBundle\Form\Type\TutorialType;

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
        $em = $this->getEm();
        $categories = $em->getRepository('DTutoBundle:Category')->findAll();
        
        return $this->render('DTutoBundle:index.html.twig', array(
            'categories' => $categories
        ));
    }

    /**
     * 
     * @Route("/new/{id}", name="discutea_tuto_create_tutorial")
     * @ParamConverter("category")
     * @Security("is_granted('ROLE_ADMIN')")
     * 
     * @param object $request Symfony\Component\HttpFoundation\Request
     * @param objct $category Discutea\DForumBundle\Entity\Category
     * 
     * @return object Symfony\Component\HttpFoundation\RedirectResponse redirecting moderator's dashboard
     * @return objet Symfony\Component\HttpFoundation\Response
     * 
     */
    public function newTutorialAction(Request $request, Category $category)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $tutorial = new Tutorial($category, $user);
        $form = $this->createForm(TutorialType::class, $tutorial, array( 'locale' => $request->getLocale() ));

        if ($form->handleRequest($request)->isValid()) {
            $tutorial->getTmpContribution()->setLocale($request->getLocale());
            $em = $this->getEm();
            $em->persist($tutorial);
            $em->persist( $tutorial->getTmpContribution() );
            $em->flush();
        }

        return $this->render('DTutoBundle:Form/tutorial.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
