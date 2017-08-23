<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\ShortUrl;

class ShortUrlType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('original_url', TextType::class, [
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Insert original URL here',
                    'class'       => 'form-control mb-3'
                ]
            ])
            ->add('short_url', TextType::class, [
                'required' => false,
                'label'    => false,
                'attr'     => [
                    'placeholder' => 'Type short URL-title if you would like',
                    'class'       => 'form-control mb-3'
                ]
            ])
            ->add('submit', SubmitType::class, array(
                'label' => 'Shorten URL',
                'attr'  => [
                    'class' => 'btn btn-primary'
                ]
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ShortUrl::class,
        ));
    }

    public function getBlockPrefix()
    {
        return 'su';
    }
}
