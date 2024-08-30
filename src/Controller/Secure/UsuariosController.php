<?php

namespace App\Controller\Secure;

use App\Entity\User;
use App\Form\UsuarioType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('secure/usuarios')]
class UsuariosController extends AbstractController
{
    #[Route('/', name: 'app_usuarios')]
    public function index(UserRepository $userRepository): Response
    {
        $data['usuarios'] = $userRepository->findBy(['isActive' => 1]);
        return $this->render('secure/usuarios/abm_usuario.html.twig', $data);
    }

    #[Route('/insertar', name: 'app_insertar_usuario')]
    public function insertar(Request $request, EntityManagerInterface $em): Response
    {
        $usuario =  new User();
        $data['form'] = $this->createForm(UsuarioType::class, $usuario);
        $data['form']->handleRequest($request);
        $data['titulo'] = 'Insertar usuario';
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
        $data['titulo'] = 'Editar usuario';
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

    #[Route('/eliminar', name: 'app_eliminar_usuario', methods: ['POST'])]
    public function eliminar(UserRepository $userRepository, Request $request, EntityManagerInterface $em): JsonResponse
    { {
            // Obtener el ID desde el cuerpo de la solicitud
            $id = $request->request->get('id') ?? null;

            // Verificar si se proporcionó un ID
            if (!$id) {
                return new JsonResponse(['success' => false, 'message' => 'ID no proporcionado.'], JsonResponse::HTTP_BAD_REQUEST);
            }

            // Buscar el usuario por ID
            $user = $userRepository->findOneBy(['id' => $id, 'isActive' => true]);

            // Verificar si el usuario existe
            if (!$user) {
                return new JsonResponse(['success' => false, 'message' => 'Usuario no encontrado.'], JsonResponse::HTTP_NOT_FOUND);
            }

            try {
                // Eliminar el usuario
                $user->setActive(false);
                $em->persist($user);
                $em->flush();

                return new JsonResponse(['success' => true, 'message' => 'Usuario eliminado con éxito.', 'title' => 'Eliminado!']);
            } catch (\Exception $e) {
                // Manejo de errores
                return new JsonResponse(['success' => false, 'message' => 'Error al eliminar el usuario.', 'title' => 'Error!'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }
}
