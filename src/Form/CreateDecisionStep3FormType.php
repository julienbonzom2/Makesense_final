<?php

namespace App\Form;

use App\Entity\OngoingDecision;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateDecisionStep3FormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ongoingImpactRate', ChoiceType::class, [
                'label' => 'Define an impact rate from S to XL',
                'placeholder' => 'Impact...',
                'label_attr' => [
                    'class' => 'titleLabel'
                ],
                'choices' => [
                    'S' => 'S',
                    'M' => 'M',
                    'L' => 'L',
                    'XL' => 'XL',
                ],
                'attr' => [
                    'class' => 'form-control input-general-styling'
                ],
                'constraints' => [
                    new NotBlank(null, 'You must choose an impact rate')
                ],
                'required' => false
            ])
            ->add('ongoingEffortRate', ChoiceType::class, [
                'label' => 'Define an effort rate from S to XL',
                'label_attr' => [
                    'class' => 'titleLabel'
                ],
                'placeholder' => 'Effort...',
                'choices' => [
                    'S' => 'S',
                    'M' => 'M',
                    'L' => 'L',
                    'XL' => 'XL',
                ],
                'attr' => [
                    'class' => 'form-control rate-select input-general-styling'
                ],
                'constraints' => [
                    new NotBlank(null, 'You must choose an effort rate'),
                ],
                'required' => false
            ])
            ->add('ongoingProfit', CKEditorType::class, [
                'label' => "Describe the decision's profit",
                'label_attr' => [
                    'class' => 'titleLabel'
                ],
                'config' => ['removePlugins' => 'elementspath'],
                'constraints' => [
                    new NotBlank(null, "The decision's profit cannot be blank")
                ],
                'required' => false
            ])
            ->add('ongoingDrawback', CKEditorType::class, [
                'label' => "Describe the decision's drawback if any",
                'label_attr' => [
                    'class' => 'titleLabel'
                ],
                'config' => ['removePlugins' => 'elementspath'],
                'constraints' => [
                    new NotBlank(null, "The decision's drawback cannot be blank")
                ],
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OngoingDecision::class,
        ]);
    }
}
