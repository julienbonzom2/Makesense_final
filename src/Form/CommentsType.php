<?php

namespace App\Form;

use App\Entity\Comments;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label_attr' => [
                    'class' => 'titleLabel'
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('isFavorable', ChoiceType::class, [
                'placeholder' => 'I am...',
                'choices' => [
                    'In favor' => true,
                    'Not in favor' => false,
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'submit-button btn ',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
//            'data_class' => Comments::class,
        ]);
    }
}
