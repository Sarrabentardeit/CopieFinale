<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Form\AppointmentType;
use App\Repository\AppointmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/appointment')]
class AppointmentController extends AbstractController
{
    #[Route('/', name: 'appointment_index', methods: ['GET'])]
    public function index(AppointmentRepository $appointmentRepository): Response
    {
        return $this->render('appointment/index.html.twig', [
            'appointments' => $appointmentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'appointment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, AppointmentRepository $appointmentRepository): Response
    {
        $appointment = new Appointment();

        // Associe automatiquement le patient connecté
        if ($this->getUser() instanceof Patient) {
            $appointment->setPatient($this->getUser());

            // Définir le statut par défaut sur "En cours"
            $appointment->setStatus('En cours');
        }

        // Créez le formulaire sans le champ `status`
        $form = $this->createForm(AppointmentType::class, $appointment, [
            'include_status' => false,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($appointment);
            $entityManager->flush();

            // Redirection vers le tableau de bord du patient
            return $this->redirectToRoute('patient_dashboard'); // Assurez-vous que 'patient_dashboard' est la bonne route
        }

        return $this->render('appointment/new.html.twig', [
            'appointment' => $appointment,
            'form' => $form->createView(),
        ]);
    }




    #[Route('/{id}', name: 'appointment_show', methods: ['GET'])]
    public function show(Appointment $appointment): Response
    {
        return $this->render('appointment/show.html.twig', [
            'appointment' => $appointment,
        ]);
    }

    #[Route('/{id}/edit', name: 'appointment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Appointment $appointment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('appointment_index');
        }

        return $this->render('appointment/edit.html.twig', [
            'appointment' => $appointment,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'appointment_delete', methods: ['POST'])]
    public function delete(Request $request, Appointment $appointment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$appointment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($appointment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('appointment_index');
    }
    #[Route('/appointment/{id}/status', name: 'appointment_change_status', methods: ['POST'])]
    public function changeStatus(Request $request, Appointment $appointment, EntityManagerInterface $entityManager): Response
    {
        $newStatus = $request->request->get('status');
        $appointment->setStatus($newStatus);

        $entityManager->flush();

        $this->addFlash('success', 'Le statut du rendez-vous a été modifié.');
        return $this->redirectToRoute('doctor_dashboard');
    }

}