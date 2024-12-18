<?php

namespace App\Controller;
use App\Form\UserType; // Import correct

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    // Gérer les Docteurs
    #[Route('/doctors', name: 'admin_manage_doctors', methods: ['GET'])]
    public function manageDoctors(UserRepository $userRepository): Response
    {
        $doctors = $userRepository->findByRole('ROLE_DOCTOR');
        return $this->render('admin/manage_doctors.html.twig', [
            'doctors' => $doctors,
        ]);
    }

    // Gérer les Administrateurs
    #[Route('/admins', name: 'admin_manage_admins', methods: ['GET'])]
    public function manageAdmins(UserRepository $userRepository): Response
    {
        $admins = $userRepository->findByRole('ROLE_ADMIN');
        return $this->render('admin/manage_admins.html.twig', [
            'admins' => $admins,
        ]);
    }

    // Voir les détails
    #[Route('/user/{id}', name: 'admin_user_show', methods: ['GET'])]
    public function showUser(User $user): Response
    {
        return $this->render('admin/show_user.html.twig', [
            'user' => $user,
        ]);
    }

    // Modifier un utilisateur
    #[Route('/user/{id}/edit', name: 'admin_user_edit', methods: ['GET', 'POST'])]
    public function editUser(Request $request, User $user, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Utilisateur mis à jour avec succès !');
            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/edit_user.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    // Supprimer un utilisateur
    #[Route('/user/{id}/delete', name: 'admin_user_delete', methods: ['POST'])]
    public function deleteUser(Request $request, User $user, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', 'Utilisateur supprimé avec succès !');
        }
        return $this->redirectToRoute('admin_dashboard');
    }
}
