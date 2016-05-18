<?php
namespace Discutea\DTutoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
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
        if ($options['locale'] === NULL) {
            $locale = 'en';
        } else {
            $locale = $options['locale'];
        }
        
        $builder
            
            ->add('translations', TranslationsType::class, array(
                'fields' => array(
                    'slug' => array(
                        'display' => false
                    )
                )
            ))
            ->add('image', UrlType::class, array('required' => false))
            ->add('tmpContribution', ContributionType::class)
            ->add('category', EntityType::class, array(
                'class' => 'DTutoBundle:Category',
                'choice_label' => 'translations['.$locale.'].title',
            ))
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
            'data_class' => 'Discutea\DTutoBundle\Entity\Tutorial',
            'locale' => NULL
        ));
    }
}
