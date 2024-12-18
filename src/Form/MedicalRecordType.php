<?php

namespace App\Form;

use App\Entity\MedicalRecord;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MedicalRecordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('diagnosis', TextType::class, [
                'label' => 'Diagnostic',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('treatmentPlan', TextType::class, [
                'label' => 'Plan de traitement',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('createdDate', DateType::class, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MedicalRecord::class,
        ]);
    }
}
