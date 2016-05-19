<?php
namespace Discutea\DTutoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Discutea\DTutoBundle\Form\Type\ContributionType;

class TutorialType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('image', UrlType::class, array('required' => false))
            ->add('tmpContrib', ContributionType::class)
            ->add('save', SubmitType::class)
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
