<?php

namespace FlickrBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CategoryType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FlickrBundle\Entity\Category',
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder    
            ->add('title', TextType::class, array('label' => 'Category Title'))
            ->add('tag', TextType::class, array ('label' => 'Category Tag'))
            ->add('description', TextareaType::class, array('label' => 'Category Description'));
    }

    /*
     * The getName() method returns the identifier of this form "type".
     * Remember: They should be unique in all the third-party bundles installed 
     * in your application.
     */
    public function getName()
    {
        return 'nesa_flickr_category';
    }
} 

