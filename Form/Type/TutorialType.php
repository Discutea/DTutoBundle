<?php
namespace Discutea\DTutoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Discutea\DTutoBundle\Form\Type\ContributionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
/**
 * 
 * @package  DTutoBundle
 * @author   David Verdier <contact@discutea.com>
 * https://www.linkedin.com/in/verdierdavid
 *
 */
class TutorialType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array('label' => 'discutea.tuto.form.tuto.title'))
            ->add('description', TextType::class, array('label' => 'discutea.tuto.form.tuto.description'))
            ->add('tmpContrib', ContributionType::class, array('label' => false))
        ;
    }

    public function getName()
    {
        return 'DTutoBundle.tutorials';
    }
  
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Discutea\DTutoBundle\Entity\Tutorial'
        ));
    }
}
