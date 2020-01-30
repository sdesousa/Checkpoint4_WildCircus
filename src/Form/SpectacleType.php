<?php

namespace App\Form;

use App\Entity\Act;
use App\Entity\Spectacle;
use App\Repository\ActRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SpectacleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('place', TextType::class, [
                'label' => 'Lieu',
            ])
            ->add('date', DateTimeType::class, [
                'label' => 'Date'
            ])
            ->add('capacity', NumberType::class,[
                'label' => 'Capacité'
            ])
            ->add('posterFile', VichImageType::class, [
                'label'             => 'Photo',
                'download_link'     => false,
                'allow_delete'      => false,
                'required' => false,
            ])
            ->add('acts', EntityType::class, [
                'class' => Act::class,
                'label' => 'Numéros',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
                'choice_label' => 'name',
                'query_builder' => function (ActRepository $actRepository) {
                    return $actRepository->createQueryBuilder('a')
                        ->orderBy('a.name', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Spectacle::class,
        ]);
    }
}
