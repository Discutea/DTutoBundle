<?php
namespace Discutea\DTutoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

/**
 * 
 * @package  DTutoBundle
 * @author   David Verdier <contact@discutea.com>
 * https://www.linkedin.com/in/verdierdavid
 *
 */
class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array("label"=>"discutea.tuto.form.category.title"))
            ->add('position', TextType::class, array("label"=>"discutea.tuto.form.category.position"))
            ->add('image', UrlType::class, array("label"=>"discutea.tuto.form.category.image", 'required' => false))
        ;
    }

    public function getName()
    {
        return 'tutorial_new_category';
    }
  
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Discutea\DTutoBundle\Entity\Category'
        ));
    }
}
