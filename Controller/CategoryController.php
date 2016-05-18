<?php
namespace Discutea\DTutoBundle\Controller;

use Discutea\DTutoBundle\Controller\Base\BaseCategoryController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

use Discutea\DTutoBundle\Entity\Category;
use Discutea\DTutoBundle\Form\Type\CategoryType;

/**
 * CategoryController 
 * 
 * This class contains actions methods for forum.
 * This class extends BaseCategoryController.
 * 
 * @package  DForumBundle
 * @author   David Verdier <contact@discutea.com>
 * @access   public
 */
class CategoryController extends BaseCategoryController
{
    /**
     * 
     * @Route("cat/create", name="discutea_tuto_create_category")
     * @Security("is_granted('ROLE_ADMIN')")
     * 
     * 
     * @param object $request Symfony\Component\HttpFoundation\Request
     * 
     * @return object Symfony\Component\HttpFoundation\RedirectResponse moderator's dashboard
     * @return objet Symfony\Component\HttpFoundation\Response
     * 
     */
    public function newCategoryAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getEm();
            $em->persist($category);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.tuto.category.created'));
            return $this->redirect($this->generateUrl('discutea_tuto_admin_dashboard'));
        }

        return $this->render('DTutoBundle:Form/category.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * 
     * @Route("cat/edit/{id}", name="discutea_tuto_edit_category")
     * @ParamConverter("category")
     * @Security("is_granted('ROLE_ADMIN')")
     * 
     * 
     * @param object $request Symfony\Component\HttpFoundation\Request
     * @param objct $category Discutea\DTutoBundle\Entity\Category
     * 
     * @return object Symfony\Component\HttpFoundation\RedirectResponse moderator's dashboard
     * @return objet Symfony\Component\HttpFoundation\Response
     * 
     */
    public function editCategoryAction(Request $request, Category $category)
    {   
        $form = $this->createForm(CategoryType::class, $category);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getEm();
            $em->persist($category);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.tuto.category.edit'));
            return $this->redirect($this->generateUrl('discutea_tuto_admin_dashboard'));
        }

        return $this->render('DTutoBundle:Form/category.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * 
     * @Route("cat/remove/{id}", name="discutea_tuto_remove_category")
     * @ParamConverter("category")
     * @Security("is_granted('ROLE_ADMIN')")
     * 
     * @param object $request Symfony\Component\HttpFoundation\Request
     * @param objct $category Discutea\DTutoBundle\Entity\Category
     * 
     * @return object Symfony\Component\HttpFoundation\RedirectResponse moderator's dashboard
     * @return objet Symfony\Component\HttpFoundation\Response
     * 
     */
    public function removeCategoryAction(Request $request, Category $category)
    {

        $form = $this->getFormRemoverCategory($category);
        
        if ($form->handleRequest($request)->isValid()) {
            if ($form->getData()['purge'] === false) {
                $tutorials = $category->getTutorials();
                $newCat = $this->getEm()->getRepository('DTutoBundle:Category')->find($form->getData()['movedTo']) ;
                
                foreach ($tutorials as $tutorial) { $tutorial->setCategory($newCat); }
                $em = $this->getEm();
                $em->flush();
                $em->clear();
                $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.tuto.category.ismoved'));
            }
            
            $category = $this->getEm()->getRepository('DTutoBundle:Category')->find($category->getId()); // Fix detach error;
            $this->getEm()->remove($category);
            $this->getEm()->flush();

            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.tuto.category.delete'));
            return $this->redirect($this->generateUrl('discutea_tuto_admin_dashboard'));
        }
 
        return $this->render('DTutoBundle:Form/remove_category.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
