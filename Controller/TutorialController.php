<?php
namespace Discutea\DTutoBundle\Controller;

use Discutea\DTutoBundle\Controller\Base\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Discutea\DTutoBundle\Entity\Tutorial;
use Discutea\DTutoBundle\Entity\Category;
use Discutea\DTutoBundle\Entity\Contribution;
use Discutea\DTutoBundle\Form\Type\TutorialType;
use Discutea\DTutoBundle\Form\Type\ContributionType;

/**
 * TutorialController 
 * 
 * @package  DTutoBundle
 * @author   David Verdier <contact@discutea.com>
 * https://www.linkedin.com/in/verdierdavid
 *
 */
class TutorialController extends BaseController
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

        return $this->render('DTutoBundle::index.html.twig', array(
            'categories' => $categories
        ));
    }

    /**
     * 
     * @Route("/new/{id}", name="discutea_tuto_create_tutorial")
     * @ParamConverter("category")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
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
        $tutorial = new Tutorial();
        $tutorial->setCategory($category);
        
        $form = $this->createForm(TutorialType::class, $tutorial);

        if ($form->handleRequest($request)->isValid()) {
            // Hydrate contribution
            $contrib = $tutorial->getTmpContrib();
            $contrib->setContributor($user);
            $contrib->setCurrent(true);

            // Persist Tutorial and Contribution
            $em = $this->getEm();
            $em->persist( $tutorial );
            $em->persist( $contrib );
            $em->flush();
            
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.tuto.tutorial.create'));
            return $this->redirect($this->generateUrl('discutea_tuto_show_tutorial', array('slug' => $tutorial->getSlug())));
        }

        return $this->render('DTutoBundle::Form/tutorial.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * 
     * @Route("/show/{slug}", name="discutea_tuto_show_tutorial")
     * @ParamConverter("tutorial")
     * 
     * @Security("is_granted('CanReadTutorial', tutorial)")
     * 
     */
    public function tutorialAction(Tutorial $tutorial)
    {

        return $this->render('DTutoBundle::tutorial.html.twig', array(
            'tutorial' => $this->sortsContribs($tutorial),
            'contribution' => $tutorial->getCurrent(),
            'current' => true
        ));
    }

    /**
     * 
     * @Route("/show/{slug}/{id}", name="discutea_tuto_show_other_contrib")
     * @ParamConverter("tutorial", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("contribution", options={"mapping": {"id": "id"}})
     * 
     * @Security("is_granted('CanReadTutorial', tutorial) and is_granted('CanReadContrib', contribution)")
     * 
     */
    public function otherContribAction(Tutorial $tutorial, Contribution $contribution)
    {
        return $this->render('DTutoBundle::tutorial.html.twig', array(
            'tutorial' => $this->sortsContribs($tutorial),
            'contribution' => $contribution,
            'current' => false
        ));
    }

    /**
     * 
     * @Route("/add/contrib/{id}", name="discutea_tuto_addcontrib_tutorial")
     * @ParamConverter("tutorial")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     * 
     * @param object $request Symfony\Component\HttpFoundation\Request
     * @param objct $tutorial Discutea\DForumBundle\Entity\Tutorial
     * 
     * @return object Symfony\Component\HttpFoundation\RedirectResponse redirecting moderator's dashboard
     * @return objet Symfony\Component\HttpFoundation\Response
     * 
     */
    public function addContribAction(Request $request, Tutorial $tutorial)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $tutorial->setTmpContrib( new Contribution( $user ) );
        $contrib = $tutorial->getTmpContrib();
        $form = $this->createForm(ContributionType::class, $contrib);
        
        if ($form->handleRequest($request)->isValid()) {
            
 
            $em = $this->getEm();
            $em->persist( $contrib );
            $em->flush();
            
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.tuto.contrib.add'));
            return $this->redirect($this->generateUrl('discutea_tuto_show_tutorial', array('slug' => $tutorial->getSlug())));
        }

        return $this->render('DTutoBundle::Form/contribution.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * 
     * @Route("/toto/remove/{id}", name="discutea_tuto_tuto_remove")
     * @ParamConverter("tutorial")
     * @Security("is_granted('ROLE_MODERATOR')")
     * 
     * @param object $request Symfony\Component\HttpFoundation\Request
     * @param objct $tutorial Discutea\DForumBundle\Entity\Contribution
     * 
     * @return object Symfony\Component\HttpFoundation\RedirectResponse redirecting moderator's dashboard
     * @return objet Symfony\Component\HttpFoundation\Response
     * 
     */
    public function removeAction(Request $request, Tutorial $tutorial)
    {
        $em = $this->getEm();
        $em->remove($tutorial);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.tuto.tuto.removed'));
        return $this->redirect($this->generateUrl('discutea_tuto_homepage'));
    }

    /**
     * Remove contributions not authorized
     * 
     * @param Tutorial $tutorial
     * @return Tutorial
     */
    private function sortsContribs(Tutorial $tutorial)
    {
        $authorizer = $this->getAuthorization();
        
        foreach ($tutorial->getContributions() as $contrib ) {
            if ( false === $authorizer->isGranted('CanReadContrib', $contrib) ) {
                $tutorial->removeContribution($contrib);
            }
        }
        
        return $tutorial;
    }    
}
