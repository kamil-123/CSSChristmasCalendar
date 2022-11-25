<?php

namespace App\Form;

use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class PersonFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isAdmin = $options['isAdmin'];

        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Jméno',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Příjmení',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('giftName', TextType::class, [
                'label' => 'Název dárku',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Uložit'
            ]);

        if ($isAdmin) {
            $builder
                ->add('file', FileType::class, [
                    'label' => 'Fotka nebo obrázek',
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new Image([
                            'maxSize' => '5M'
                        ])
                    ]
                ])
                ->add('active', CheckboxType::class, [
                    'label' => 'Aktivace'
                ]);
        } else {
            $builder
                ->add('file', FileType::class, [
                    'label' => 'Fotka nebo obrázek',
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new Image([
                            'maxSize' => '5M'
                        ]),
                        new NotBlank()
                    ]
                ])
                ->add('phrase', TextType::class, [
                    'label' => 'Registační fráze',
                    'mapped' => false,
                    'constraints' => [
                        new NotBlank(),
                        new Regex('/\b(usetrenoAdvent)\b/')
                    ]
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
            'isAdmin' => false,
        ]);
    }
}