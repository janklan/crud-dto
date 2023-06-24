<?php

namespace App\Form;

use App\Crud\PersonCreateDto;
use App\Entity\Person;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', options: [
                'attr' => [
                    'autofocus' => true,
                ],
            ])
            ->add('boss', EntityType::class, [
                'class' => Person::class,
            ])
            ->add('address', AddressType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'empty_data' => fn () => new PersonCreateDto(),
            'attr' => ['novalidate' => 'novalidate'],
            'data_class' => PersonCreateDto::class,
        ]);
    }
}
