<?php

namespace Discutea\DTutoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Discutea\DTutoBundle\Form\Type\Model\AbstractContributionType;

class ContributionModeratorType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
                ->add('status', ChoiceType::class, array(
                     'choices'  => array(
                         'Je terminerais plus tard' => 0,
                         'j\'ai terminé le tutoriel' => 2,
                         'Reffuser le tutoriel' => 1,
                         'Valider le tutoriel' => 4,
                )))
                ->add('message')
        ;
    }
    
    public function getParent()
    {
        return AbstractContributionType::class;
    }
}
