<?php

namespace Discutea\DTutoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Discutea\DTutoBundle\Form\Type\Model\AbstractContributionType;

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
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
                ->add('status', ChoiceType::class, array(
                     'label'    => 'discutea.tuto.form.contrib.status',
                     'choices'  => array(
                         'discutea.tuto.form.contrib.status.0' => 0,
                         'discutea.tuto.form.contrib.status.2' => 2
                )))
        ;
    }
    
    public function getParent()
    {
        return AbstractContributionType::class;
    }
}
