<?php
namespace Discutea\DTutoBundle\Form\Type;

use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

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
        $choices = array(
                'discutea.tuto.form.contrib.status.0' => 0,
                'discutea.tuto.form.contrib.status.2' => 2
        );
        
        if (true === $this->authorizer->isGranted('ROLE_MODERATOR') ) {
            $choices['discutea.tuto.form.contrib.status.1'] = 1;
            $choices['discutea.tuto.form.contrib.status.3'] = 3;
        }
            
        $builder
                ->add('content', TextareaType::class, array(
                    'label'=>'discutea.tuto.form.contribution.content'
                ))
                ->add('version', TextType::class, array(
                    'label'    => 'discutea.tuto.form.contrib.version',
                    'required' => false
                ))
                ->add('status', ChoiceType::class, array(
                'label'    => 'discutea.tuto.form.contrib.status',
                'choices'  => $choices
            ));
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
