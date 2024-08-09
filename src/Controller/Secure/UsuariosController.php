<?php

namespace App\Controller\Secure;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('secure/usuarios')]
class UsuariosController extends AbstractController
{
    #[Route('/', name: 'app_usuarios')]
    public function index(UserRepository $userRepository): Response
    {
        $data['usuarios'] = $userRepository->findAll();
        foreach ($data['usuarios'] as $usuario) {
            dump($usuario->getEmail());
        }
        die();
        return $this->render('usuarios/index.html.twig', [
            'controller_name' => 'UsuariosController',
        ]);
    }
}
