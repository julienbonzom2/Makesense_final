<?php

namespace App\Form;

use App\DataFixtures\CategoryFixtures;
use App\Entity\OngoingDecision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateDecisionStep1FormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ongoingTitle', TextType::class, [
//                'label' => 'Title',
                'label_attr' => [
                    'class' => 'titleLabel'
                ],
                'attr' => [
                    'class' => 'titleInput form-control input-general-styling',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(max: 255, maxMessage: ('The title cannot be longer than 255 characters'))
                ],
                'required' => false,
            ])
            ->add('category', null, [
                'label_attr' => [
                    'class' => 'categoryLabel'
                ],
                'choice_label' => 'categoryName',
                'placeholder' => 'Choose a category',
                'attr' => [
                    'class' => 'categoryInput form-control input-general-styling'
                ],
                'constraints' => [
                    new NotBlank(null, 'You must choose a category')
                ],
                'required' => false,
            ])
            ->add('ongoingProblem', CKEditorType::class, [
                'label' => 'Describe the problem you wish to work on',
                'label_attr' => [
                    'class' => 'problemLabel'
                ],
                'attr' => [
                    'class' => 'textEditor'
                ],
                'config' => [
                    'toolbar' => 'basic',
                    'removePlugins' => 'elementspath',
                    'stylesSet' => 'my_styles',
                ],
                'styles' => [
                    'my_styles' => [
                        ['name' => 'Blue Title', 'element' => 'h2', 'styles' => ['color' => 'Blue']],
                    ]
                ],
                'constraints' => [
                    new NotBlank(null, 'The problem should not be blank'),
                ],
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OngoingDecision::class,
            'class' => 'step1Form',
        ]);
    }
}
