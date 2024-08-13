<?php

namespace App\Controller\Secure;

use App\Entity\User;
use App\Form\UsuarioType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('secure/usuarios')]
class UsuariosController extends AbstractController
{
    #[Route('/', name: 'app_usuarios')]
    public function index(UserRepository $userRepository): Response
    {
        $data['usuarios'] = $userRepository->findAll();
        return $this->render('secure/usuarios/abm_usuario.html.twig', $data);
    }

    #[Route('/insertar', name: 'app_insertar_usuario')]
    public function insertar(Request $request, EntityManagerInterface $em): Response
    {
        $usuario =  new User();
        $data['form'] = $this->createForm(UsuarioType::class, $usuario);
        $data['form']->handleRequest($request);
        if ($data['form']->isSubmitted() && $data['form']->isValid()) {
            // $data['form']->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $em->persist($usuario);
            $em->flush();
            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('app_usuarios');
        }
        return $this->render('secure/usuarios/form_usuario.html.twig', $data);
    }

    #[Route('/editar/{usuario_id}', name: 'app_editar_usuario')]
    public function editar($usuario_id, UserRepository $userRepository, Request $request, EntityManagerInterface $em): Response
    {
        $data['usuario'] =  $userRepository->findOneBy(['id' => $usuario_id]);
        if (!$data['usuario']) {
            return $this->redirectToRoute('app_usuarios');
        }
        $data['form'] = $this->createForm(UsuarioType::class, $data['usuario']);
        $data['form']->handleRequest($request);
        if ($data['form']->isSubmitted() && $data['form']->isValid()) {
            // $data['form']->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $em->persist($data['usuario']);
            $em->flush();
            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('app_usuarios');
        }
        return $this->render('secure/usuarios/form_usuario.html.twig', $data);
    }
}
