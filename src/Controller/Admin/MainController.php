<?php

namespace App\Controller\Admin;

use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/admin', name: 'admin_')]
class MainController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function users(UsersRepository $users): Response
    {
        $users = $users->findBy([], ['created_at' => 'DESC']);

        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }
   
}
