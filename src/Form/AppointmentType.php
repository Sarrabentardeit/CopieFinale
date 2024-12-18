<?php
namespace App\Form;

use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Entity\Patient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateTime', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date et heure du rendez-vous',
            ])
            ->add('doctor', EntityType::class, [
                'class' => Doctor::class,
                'choice_label' => 'username',
                'label' => 'Médecin',
            ])
            ->add('patient', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => 'username',
                'label' => 'Patient',
            ])
            ->add('notes', TextareaType::class, [
                'required' => false,
                'label' => 'Notes supplémentaires',
            ]);

        // Ajouter le champ `status` uniquement si l'option `include_status` est activée
        if ($options['include_status']) {
            $builder->add('status', ChoiceType::class, [
                'choices' => [
                    'En cours' => 'En cours',
                    'Confirmé' => 'Confirmé',
                    'Annulé' => 'Annulé',
                ],
                'label' => 'Statut du rendez-vous',
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
            'include_status' => false, // Par défaut, le champ `status` est exclu
        ]);
    }
}
