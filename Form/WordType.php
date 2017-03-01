<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WordType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {   // like previous versions setDefuat options ...
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Word'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {   //its like previous version getName method 
        return 'appbundle_word';
    }


}
