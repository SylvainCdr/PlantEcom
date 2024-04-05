<?php

namespace App\Controller\Admin;

use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/admin/utilisateurs', name: 'admin_users_')]
class UsersController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(UsersRepository $usersRepository): Response
    {

        $users = $usersRepository->findBy([], ['lastname' => 'ASC']);


        return $this->render('admin/users/index.html.twig', [
            'users' => $users,

        ]);
    }

    #[Route('edition/{id}', name: 'edit', methods: ['GET'])]
    public function edit(UsersRepository $usersRepository, $id): Response
    {
        $user = $usersRepository->find($id);

        return $this->render('admin/users/edit.html.twig', [
            'user' => $user,
        ]);
    }
}
