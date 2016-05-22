<?php
namespace Discutea\DTutoBundle\Form\Type;

use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * 
 * @package  DTutoBundle
 * @author   David Verdier <contact@discutea.com>
 * https://www.linkedin.com/in/verdierdavid
 *
 */
class ContributionType extends AbstractType
{
    /**
     *
     * @var type Symfony\Component\Security\Core\Authorization\AuthorizationChecker
     */
    protected $authorizer;

    /**
     * 
     * @param AuthorizationChecker $authorizer
     */    
    public function __construct(AuthorizationChecker $authorizer) {
        $this->authorizer = $authorizer;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('content', TextareaType::class, array("label"=>"discutea.tuto.form.contribution.content"));
        
        if (false === $this->authorizer->isGranted('ROLE_MODERATOR') ) {
            
            $builder->add('status', ChoiceType::class, array(
                'label'    => 'discutea.tuto.form.contrib.status',
                'choices'  => array(
                    'discutea.tuto.form.contrib.status.0' => 0,
                    'discutea.tuto.form.contrib.status.2' => 2
            )));
            
        } else {
            
            $builder
                ->add('status', ChoiceType::class, array(
                    'label'    => 'discutea.tuto.form.contrib.status',
                     'choices' => array(
                         'discutea.tuto.form.contrib.status.0' => 0,
                         'discutea.tuto.form.contrib.status.2' => 2,
                         'discutea.tuto.form.contrib.status.1' => 1,
                         'discutea.tuto.form.contrib.status.3' => 3,
                )))
                ->add('message', TextType::class, array(
                    'label'    => 'discutea.tuto.form.contrib.message',
                    'required' => false
            ));
            
        }
    }
    
    public function getName()
    {
        return 'DTutoBundle.contribution';
    }
  
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Discutea\DTutoBundle\Entity\Contribution'
        ));
    }
}
