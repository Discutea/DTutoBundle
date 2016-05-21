<?php

namespace Discutea\DTutoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Discutea\DTutoBundle\Form\Type\Model\AbstractContributionType;

/**
 * 
 * @package  DTutoBundle
 * @author   David Verdier <contact@discutea.com>
 * https://www.linkedin.com/in/verdierdavid
 *
 */
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
                    'label'    => 'discutea.tuto.form.contrib.status',
                     'choices' => array(
                         'discutea.tuto.form.contrib.status.0' => 0,
                         'discutea.tuto.form.contrib.status.2' => 2,
                         'discutea.tuto.form.contrib.status.1' => 1,
                         'discutea.tuto.form.contrib.status.4' => 3,
                )))
                ->add('message', TextType::class, array('label' => 'discutea.tuto.form.contrib.message'))
        ;
    }
    
    public function getParent()
    {
        return AbstractContributionType::class;
    }
}
