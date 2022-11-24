<?php

namespace App\Form;

use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class PersonFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Jmeno:',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Prijmeni:',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('giftName', TextType::class, [
                'label' => 'Nazev darku:',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('file', FileType::class, [
                'label' => 'Obrazek:',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '5M'
                    ]),
                    new NotBlank,
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Ulozit'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Person::class
        ]);
    }
}