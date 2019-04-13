<?php

namespace App\Form;

use App\Entity\Trip;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('departure_place')
            ->add('arrival_place')
            ->add('departure_schedule', DateTimeType::class, [
                'view_timezone' => 'America/New_York',
                'widget' => 'choice',
                'years' => range(date('Y'), date('Y')+3),
                'date_format' => 'd MMMM y',
                'attr' => [
                    "class" => ""
                ]
            ])
            ->add('duration', TimeType::class)
            ->add('nbr_places')
            ->add('price')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
