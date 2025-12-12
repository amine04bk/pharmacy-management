<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/users')]
class AdminUserController extends AbstractController
{
    #[Route('/', name: 'admin_users_index')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAllOrdered();

        return $this->render('admin/users/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/role/{role}', name: 'admin_users_by_role')]
    public function byRole(string $role, UserRepository $userRepository): Response
    {
        $users = $userRepository->findByRole($role);

        return $this->render('admin/users/by_role.html.twig', [
            'users' => $users,
            'role' => $role,
        ]);
    }

    #[Route('/{id}/toggle-active', name: 'admin_users_toggle_active', methods: ['POST'])]
    public function toggleActive(int $id, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->find($id);
        
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $user->setIsActive(!$user->isActive());
        $entityManager->flush();

        $this->addFlash('success', 'User status updated successfully');
        
        return $this->redirectToRoute('admin_users_index');
    }

    #[Route('/{id}/edit', name: 'admin_users_edit')]
    public function edit(
        int $id,
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = $userRepository->find($id);
        
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        if ($request->isMethod('POST')) {
            $user->setName($request->request->get('name'));
            $user->setEmail($request->request->get('email'));
            $user->setPhone($request->request->get('phone'));
            $user->setAddress($request->request->get('address'));
            $user->setCity($request->request->get('city'));
            
            $password = $request->request->get('password');
            if ($password) {
                $hashedPassword = $passwordHasher->hashPassword($user, $password);
                $user->setPassword($hashedPassword);
            }

            $entityManager->flush();

            $this->addFlash('success', 'User updated successfully');
            return $this->redirectToRoute('admin_users_index');
        }

        return $this->render('admin/users/edit.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_users_delete', methods: ['POST'])]
    public function delete(int $id, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->find($id);
        
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'User deleted successfully');
        
        return $this->redirectToRoute('admin_users_index');
    }
}
