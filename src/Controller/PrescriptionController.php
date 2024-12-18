<?php

namespace App\Controller;

use App\Entity\Prescription;
use App\Form\PrescriptionType;
use App\Repository\PrescriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\MedicalRecordRepository;


#[Route('/prescription')]
final class PrescriptionController extends AbstractController
{
    #[Route(name: 'app_prescription_index', methods: ['GET'])]
    public function index(
        PrescriptionRepository $prescriptionRepository,
        MedicalRecordRepository $medicalRecordRepository // Ajout de ce paramètre
    ): Response {
        // Récupérer tous les dossiers médicaux
        $medicalRecords = $medicalRecordRepository->findAll();

        // Rendre le template en passant les prescriptions et dossiers médicaux
        return $this->render('prescription/index.html.twig', [
            'prescriptions' => $prescriptionRepository->findAll(),
            'medical_records' => $medicalRecords, // Transmettre les dossiers médicaux au template
        ]);
    }


    #[Route('/new/{medicalRecordId}', name: 'app_prescription_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        MedicalRecordRepository $medicalRecordRepository,
        int $medicalRecordId
    ): Response {
        // Rechercher le dossier médical
        $medicalRecord = $medicalRecordRepository->find($medicalRecordId);

        if (!$medicalRecord) {
            throw $this->createNotFoundException('Dossier médical introuvable.');
        }

        // Créer une nouvelle prescription et la lier au dossier médical
        $prescription = new Prescription();
        $prescription->setMedicalRecord($medicalRecord);

        $form = $this->createForm(PrescriptionType::class, $prescription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($prescription);
            $entityManager->flush();

            $this->addFlash('success', 'Prescription créée avec succès.');
            return $this->redirectToRoute('app_prescription_index');
        }

        return $this->render('prescription/new.html.twig', [
            'prescription' => $prescription,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_prescription_show', methods: ['GET'])]
    public function show(Prescription $prescription): Response
    {
        return $this->render('prescription/show.html.twig', [
            'prescription' => $prescription,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_prescription_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Prescription $prescription, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PrescriptionType::class, $prescription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_prescription_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('prescription/edit.html.twig', [
            'prescription' => $prescription,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_prescription_delete', methods: ['POST'])]
    public function delete(Request $request, Prescription $prescription, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prescription->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($prescription);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_prescription_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/view/{medicalRecordId}', name: 'app_prescription_view', methods: ['GET'])]
    public function viewPrescriptions(
        int $medicalRecordId,
        MedicalRecordRepository $medicalRecordRepository
    ): Response {
        // Rechercher le dossier médical par ID
        $medicalRecord = $medicalRecordRepository->find($medicalRecordId);

        if (!$medicalRecord) {
            throw $this->createNotFoundException('Dossier médical introuvable.');
        }

        // Récupérer les prescriptions associées au dossier médical
        $prescriptions = $medicalRecord->getPrescriptions();

        return $this->render('prescription/view.html.twig', [
            'medicalRecord' => $medicalRecord,
            'prescriptions' => $prescriptions,
        ]);
    }

}
