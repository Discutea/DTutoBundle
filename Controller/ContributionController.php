<?php
namespace Discutea\DTutoBundle\Controller;

use Discutea\DTutoBundle\Controller\Base\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Discutea\DTutoBundle\Entity\Contribution;
use Discutea\DTutoBundle\Entity\Tutorial;
use Discutea\DTutoBundle\Form\Type\ContributionType;
use Discutea\DTutoBundle\Form\Type\ContributionModeratorType;

/**
 * ContributionController 
 * 
 * @package  DTutoBundle
 * @author   David Verdier <contact@discutea.com>
 * https://www.linkedin.com/in/verdierdavid
 *
 */
class ContributionController extends BaseController
{
    /**
     * 
     * @Route("/contrib/edit/{id}", name="discutea_tuto_contrib_edit")
     * @ParamConverter("contribution")
     * @Security("is_granted('CanEditContribution', contribution)")
     * 
     * @param object $request Symfony\Component\HttpFoundation\Request
     * @param objct $contribution Discutea\DForumBundle\Entity\Contribution
     * 
     */
    public function editAction(Request $request, Contribution $contribution)
    {
        if (true === $this->getAuthorization()->isGranted('ROLE_MODERATOR')) {
            $form = $this->createForm(ContributionModeratorType::class, $contribution);
        } else {
            $form = $this->createForm(ContributionType::class, $contribution);
        }

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getEm();
            $em->persist( $contribution );
            $em->flush();
            
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.tuto.contrib.edit'));
            return $this->redirect($this->generateUrl('discutea_tuto_show_tutorial', array('slug' => $contribution->getTutorial()->getSlug())));
        }

        return $this->render('DTutoBundle:Form/contribution.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * 
     * @Route("/contrib/remove/{id}", name="discutea_tuto_contrib_remove")
     * @ParamConverter("contribution")
     * @Security("is_granted('ROLE_MODERATOR')")
     * 
     * @param object $request Symfony\Component\HttpFoundation\Request
     * @param objct $contribution Discutea\DForumBundle\Entity\Contribution
     * 
     * @return object Symfony\Component\HttpFoundation\RedirectResponse redirecting moderator's dashboard
     * @return objet Symfony\Component\HttpFoundation\Response
     * 
     */
    public function removeAction(Request $request, Contribution $contribution)
    {
        if (true === $contribution->getCurrent()) {
            $request->getSession()->getFlashBag()->add('danger', $this->getTranslator()->trans('discutea.tuto.contrib.current.notremoved'));
            return $this->redirect($this->generateUrl('discutea_tuto_show_tutorial', array('slug' => $contribution->getTutorial()->getSlug())));
        }

        $em = $this->getEm();
        $em->remove($contribution);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.tuto.contrib.removed'));
        return $this->redirect($this->generateUrl('discutea_tuto_show_tutorial', array('slug' => $contribution->getTutorial()->getSlug())));
    }

    /**
     * 
     * @Route("/setactive/{tid}/{cid}", name="discutea_tuto_setactive_contrib")
     * @ParamConverter("tutorial", options={"mapping": {"tid": "id"}})
     * @ParamConverter("contribution", options={"mapping": {"cid": "id"}})
     * @Security("is_granted('ROLE_MODERATOR')")
     * 
     */
    public function activeContribAction(Request $request, Tutorial $tutorial, Contribution $contribution)
    {
        if ($tutorial->getCurrent() !== $contribution) {
            
            $em = $this->getEm();

            foreach ($tutorial->getContributions() as $contrib) {
                if ($contrib->getCurrent() === true) {
                    $contrib->setCurrent(false);
                    $em->persist($contrib);
                }
            }
            
            $contribution->setCurrent(true);
            $em->persist($contribution);
            $em->flush();
        }
        
        $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.tuto.setactive.contrib'));
        return $this->redirect($this->generateUrl('discutea_tuto_show_tutorial', array('slug' => $tutorial->getSlug())));
    }
}
