<?php

namespace App\Controller;

use App\Entity\MedicalRecord;
use App\Form\MedicalRecordType;
use App\Repository\AppointmentRepository;
use App\Repository\MedicalRecordRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/medical/record')]
final class MedicalRecordController extends AbstractController
{
    #[Route(name: 'app_medical_record_index', methods: ['GET'])]
    public function index(
        MedicalRecordRepository $medicalRecordRepository,
        AppointmentRepository $appointmentRepository
    ): Response {
        // Récupérer tous les dossiers médicaux
        $medicalRecords = $medicalRecordRepository->findAll();

        // Récupérer tous les rendez-vous
        $appointments = $appointmentRepository->findAll();

        return $this->render('medical_record/index.html.twig', [
            'medical_records' => $medicalRecords,
            'appointments' => $appointments,

        ]);
    }


    #[Route('/new/{appointmentId}', name: 'app_medical_record_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        AppointmentRepository $appointmentRepository,
        int $appointmentId
    ): Response {
        $appointment = $appointmentRepository->find($appointmentId);

        if (!$appointment) {
            throw $this->createNotFoundException('Rendez-vous introuvable.');
        }

        if (!$appointment->getPatient()) {
            throw $this->createNotFoundException('Aucun patient associé à ce rendez-vous.');
        }

        $medicalRecord = new MedicalRecord();
        $medicalRecord->setPatient($appointment->getPatient());
        $medicalRecord->setAppointment($appointment);

        $form = $this->createForm(MedicalRecordType::class, $medicalRecord);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $medicalRecord->setCreatedDate(new \DateTime());
            $entityManager->persist($medicalRecord);
            $entityManager->flush();

            $this->addFlash('success', 'Dossier médical créé avec succès.');
            return $this->redirectToRoute('app_medical_record_index');
        }

        return $this->render('medical_record/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/show', name: 'app_medical_record_show', methods: ['GET'])]
    public function show(MedicalRecord $medicalRecord): Response
    {
        return $this->render('medical_record/show.html.twig', [
            'medical_record' => $medicalRecord,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_medical_record_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MedicalRecord $medicalRecord, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MedicalRecordType::class, $medicalRecord);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Dossier médical mis à jour avec succès.');
            return $this->redirectToRoute('app_medical_record_index');
        }

        return $this->render('medical_record/edit.html.twig', [
            'medical_record' => $medicalRecord,
            'form' => $form->createView(),
            'patientId' => $medicalRecord->getPatient()->getId(),  // Passer patientId à la vue
        ]);
    }


    #[Route('/{id}/delete', name: 'app_medical_record_delete', methods: ['POST'])]
    public function delete(Request $request, MedicalRecord $medicalRecord, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $medicalRecord->getId(), $request->request->get('_token'))) {
            $entityManager->remove($medicalRecord);
            $entityManager->flush();

            $this->addFlash('success', 'Dossier médical supprimé avec succès.');
        }

        return $this->redirectToRoute('app_medical_record_index');
    }


    #[Route('/patient/{patientId}', name: 'app_medical_record_by_patient', methods: ['GET'])]
    public function indexByPatient(
        int $patientId,
        MedicalRecordRepository $medicalRecordRepository
    ): Response {
        // Récupérer les dossiers médicaux liés à un patient
        $medicalRecords = $medicalRecordRepository->findBy(['patient' => $patientId]);

        return $this->render('medical_record/index.html.twig', [
            'medical_records' => $medicalRecords,
            'patientId' => $patientId, // Ajout de la variable patientId
        ]);
    }
    #[Route('/medical/record/my', name: 'app_medical_record_my', methods: ['GET'])]
    public function myMedicalRecords(MedicalRecordRepository $medicalRecordRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        // Récupérer les dossiers médicaux uniquement pour le patient connecté
        $medicalRecords = $medicalRecordRepository->findBy(['patient' => $user]);

        return $this->render('medical_record/patient_index.html.twig', [
            'medical_records' => $medicalRecords,

        ]);
    }


    #[Route('/doctor/dashboard/medical-records', name: 'app_doctor_medical_records', methods: ['GET'])]
    public function doctorMedicalRecords(MedicalRecordRepository $medicalRecordRepository): Response
    {
        // Récupérer tous les dossiers médicaux avec les patients
        $medicalRecords = $medicalRecordRepository->findAll();

        return $this->render('doctor/medical_records.html.twig', [
            'medical_records' => $medicalRecords,
        ]);
    }

}
