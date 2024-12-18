<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Prescription;
use App\Repository\MedicalRecordRepository;

class PrescriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('medication', null, [
                'label' => 'Médicament',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dosage', null, [
                'label' => 'Dosage',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('startDate', null, [
                'widget' => 'single_text',
                'label' => 'Date de début',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('medicalRecord', HiddenType::class, [
                'mapped' => false, // Ce champ sera géré directement dans le contrôleur
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prescription::class,
        ]);
    }
}
