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
 * @package  DTutoBundle
 * @author   David Verdier <contact@discutea.com>
 * https://www.linkedin.com/in/verdierdavid
 *
 */
class CategoryController extends BaseCategoryController
{
    /**
     * 
     * Create a category.
     * 
     * @Route("cat/create", name="discutea_tuto_create_category")
     * @Security("is_granted('ROLE_ADMIN')")
     * 
     * @param object $request Symfony\Component\HttpFoundation\Request
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

        return $this->render('DTutoBundle::Form/category.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * 
     * Edit a category.
     * 
     * @Route("cat/edit/{id}", name="discutea_tuto_edit_category")
     * @ParamConverter("category")
     * @Security("is_granted('ROLE_ADMIN')")
     * 
     * @param object $request Symfony\Component\HttpFoundation\Request
     * @param object $category Discutea\DTutoBundle\Entity\Category
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

        return $this->render('DTutoBundle::Form/category.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * 
     * Remove a category please see Discutea\DTutoBundle\Controller\Base\BaseCategoryController.
     * 
     * @Route("cat/remove/{id}", name="discutea_tuto_remove_category")
     * @ParamConverter("category")
     * @Security("is_granted('ROLE_ADMIN')")
     * 
     * @param object $request Symfony\Component\HttpFoundation\Request
     * @param object $category Discutea\DTutoBundle\Entity\Category
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
                $em->clear(); // Fix foreach clear entitie manager
                $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.tuto.category.ismoved'));
            }
            
            $category = $this->getEm()->getRepository('DTutoBundle:Category')->find($category->getId()); // Fix detach error;
            $this->getEm()->remove($category);
            $this->getEm()->flush();

            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.tuto.category.delete'));
            return $this->redirect($this->generateUrl('discutea_tuto_admin_dashboard'));
        }
 
        return $this->render('DTutoBundle::Form/remove_category.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
