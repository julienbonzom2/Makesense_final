<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchDecisionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('search', SearchType::class, [
                'label_attr' => [
                    'class' => 'form-label py-10 d-none'
                ],
                'label' => 'Search decisions by title or author',
                'attr' => [
                    'class' => 'form-control width-search-bar   input-general-styling',
                    'placeholder' => 'Search decisions by title or author'
                ],
            ])
//            ->add('submit', SubmitType::class, [
//                'attr' => [
//                    'class' => 'btn btn-primary mt-2'
//                ],
//                'label' => 'Search'
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
