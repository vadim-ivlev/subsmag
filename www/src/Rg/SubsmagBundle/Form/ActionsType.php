<?php

namespace Rg\SubsmagBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActionsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')->add('introtext')->add('description')->add('dateStart')->add('dateEnd')->add('discount')->add('giftDescription')->add('flagVisibleOnSite')->add('flagPercentOrFix')->add('cntUsed')->add('userId')->add('productId')->add('kitId')->add('promocodeId');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Rg\SubsmagBundle\Entity\Actions'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'rg_subsmagbundle_actions';
    }


}
