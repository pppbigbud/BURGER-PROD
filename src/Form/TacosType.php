<?php

namespace App\Form;

use App\Entity\Cheese;
use App\Entity\Meat;
use App\Entity\Sauce;
use App\Entity\Size;
use App\Entity\Tacos;
use App\Repository\CheeseRepository;
use App\Repository\MeatRepository;
use App\Repository\SauceRepository;
use App\Repository\SizeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TacosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('size', EntityType::class, [
                'class' => Size::class,
                'query_builder' => function (SizeRepository $r) {
                    return $r->createQueryBuilder('s')
                        ->orderBy('s.name', 'ASC');
                },
                'label' => 'Quelle faim as-tu ?',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'choice_label' => 'name',
                'multiple' => false,

            ])
            ->add('meat', EntityType::class, [
                'class' => Meat::class,
                'query_builder' => function (MeatRepository $r) {
                    return $r->createQueryBuilder('m')
                        ->orderBy('m.name', 'ASC');
                },
                'label' => 'Viandes au choix',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'choice_label' => 'name',
                'multiple' => false,
//                'expanded'=> true,
            ])
            ->add('sauce', EntityType::class, [
                'class' => Sauce::class,
                'query_builder' => function (SauceRepository $r) {
                    return $r->createQueryBuilder('i')
                        ->orderBy('i.name', 'ASC');
                },
                'label' => 'Tu sauces ?',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'choice_label' => 'name',
                'multiple' => false,
//                'expanded'=> true,
            ])
            ->add('cheese', EntityType::class, [
                'class' => Cheese::class,
                'query_builder' => function (CheeseRepository $c) {
                    return $c->createQueryBuilder('v')
                        ->orderBy('v.name', 'ASC');
                },
                'label' => 'Quel Fromage souhaites-tu ?',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'choice_label' => 'name',
                'multiple' => false,
//                'expanded'=> true,

            ]);
//
//            ->add('submit', SubmitType::class, [
//                'attr' => [
//                    'class' => 'btn btn-primary mt-4'
//                ],
//            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tacos::class,
        ]);
    }
}
