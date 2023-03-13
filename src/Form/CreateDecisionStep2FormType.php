<?php

namespace App\Form;

use App\Entity\OngoingDecision;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateDecisionStep2FormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ongoingDescription', CKEditorType::class, [
                'label' => 'Describe your decision',
                'label_attr' => [
                    'class' => 'titleLabel'
                ],
                'config' => [
                    'config' => 'bigEditor',
                    'removePlugins' => 'elementspath',
                ],
                'constraints' => [
                    new NotBlank(null, 'The description should not be blank.')
                ],
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OngoingDecision::class,
        ]);
    }
}
