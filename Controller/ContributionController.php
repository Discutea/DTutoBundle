<?php
namespace Discutea\DTutoBundle\Controller;

use Discutea\DTutoBundle\Controller\Base\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Discutea\DTutoBundle\Entity\Contribution;
use Discutea\DTutoBundle\Form\Type\ContributionType;

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
     * @return object Symfony\Component\HttpFoundation\RedirectResponse redirecting moderator's dashboard
     * @return objet Symfony\Component\HttpFoundation\Response
     * 
     */
    public function editAction(Request $request, Contribution $contribution)
    {
        $form = $this->createForm(ContributionType::class, $contribution);

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

}
