<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Entity\Patient;
use App\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RegistrationFormType;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $roles = $form->get('roles')->getData();
            $plainPassword = $form->get('plainPassword')->getData();

            // Déterminer le type d'utilisateur
            if (in_array('ROLE_DOCTOR', $roles)) {
                $user = new Doctor();
                $user->setSpecialization($form->get('specialization')->getData());
                $user->setLicenseNumber($form->get('licenseNumber')->getData());
            } elseif (in_array('ROLE_PATIENT', $roles)) {
                $user = new Patient();
                $user->setDob($form->get('dob')->getData());
                $user->setAddress($form->get('address')->getData());
            } else {
                $user = new Admin();
            }

            // Ajout des données communes
            $user->setUsername($formData->getUsername());
            $user->setEmail($formData->getEmail());
            $user->setRoles($roles);
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // Enregistrement dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Compte créé avec succès.');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
