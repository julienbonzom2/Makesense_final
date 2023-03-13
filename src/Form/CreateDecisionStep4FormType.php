<?php

namespace App\Form;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateDecisionStep4FormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('search', EntityType::class, [
                'class' => User::class,
                'autocomplete' => true,
                'label_attr' => [
                    'class' => 'form-section-title'
                ],
                'placeholder' => 'Search...',
                'required' => false,
                'multiple' => true,
                'query_builder' => $options['query_builder'],
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'label_attr' => [
                    'class' => 'form-section-title'
                ],
                'query_builder' => $options['query_builder'],
                'choice_label' => function (User $user) {
                    return $user->getFirstname() . ' ' . $user->getLastname();
                },
                'attr' => [
                    'class' => 'form-control input-general-styling'
                ],
                'multiple' => true,
                'required' => false,
                'expanded' => true,
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'decision' => null,
            'query_builder' => function (Options $options) {
                return function (UserRepository $repo) use ($options) {
                    $notDesignatedUsersId = $repo->getUserNotDesignated($options['decision']);
                    return $repo->createQueryBuilder('u')
                        ->andWhere('u.id In(:val)')
                        ->setParameter('val', $notDesignatedUsersId, \Doctrine\DBAL\Connection::PARAM_STR_ARRAY);
                };
            },
        ]);
    }
}
