<?php

namespace App\Controller\Secure;

use App\Entity\User;
use App\Entity\UsuarioTipoNorma;
use App\Form\UsuarioType;
use App\Repository\TipoNormaRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN', message: 'No tienes permisos para acceder a esta sección')]
#[Route('secure/usuarios')]
class UsuariosController extends AbstractController
{
    #[Route('/', name: 'app_usuarios_index')]
    public function index(UserRepository $userRepository): Response
    {
        $data['usuarios'] = $userRepository->findBy(['isActive' => 1]);
        return $this->render('secure/usuarios/abm_usuario.html.twig', $data);
    }

    #[Route('/insertar', name: 'app_insertar_usuario')]
    public function insertar(Request $request, EntityManagerInterface $em, TipoNormaRepository $tipoNormaRepository): Response
    {
        $data['usuario'] =  new User();
        $data['form'] = $this->createForm(UsuarioType::class, $data['usuario']);
        $data['form']->handleRequest($request);
        $data['titulo'] = 'Insertar usuario';
        $data['files_js'] = [
            'usuarios/usuarios.js',
        ];
        if ($data['form']->isSubmitted() && $data['form']->isValid()) {

            $dataForm = $data['form']->getData();

            $rol = $dataForm->getRol()->getNombre();
            if ($rol === 'ROLE_ADMIN') {
                $selectedTipoNormas = $tipoNormaRepository->findBy(['isActive' => true]);
            } else {
                $selectedTipoNormas = $data['form']->get('tipoNormas')->getData();
            }
            foreach ($selectedTipoNormas as $tipoNorma) {
                $usuarioTipoNorma = new UsuarioTipoNorma();
                $usuarioTipoNorma->setUser($data['usuario'])
                    ->setTipoNorma($tipoNorma);
                $em->persist($usuarioTipoNorma);
            }

            $em->persist($data['usuario']);
            $em->flush();
            return $this->redirectToRoute('app_usuarios_index');
        }
        return $this->render('secure/usuarios/form_usuario.html.twig', $data);
    }

    #[Route('/editar/{usuario_id}', name: 'app_editar_usuario')]
    public function editar($usuario_id, UserRepository $userRepository, Request $request, EntityManagerInterface $em, TipoNormaRepository $tipoNormaRepository): Response
    {
        $data['usuario'] =  $userRepository->findOneBy(['id' => $usuario_id]);
        if (!$data['usuario']) {
            return $this->redirectToRoute('app_usuarios_index');
        }

        $data['form'] = $this->createForm(UsuarioType::class, $data['usuario'], ['is_edit' => true]);

        // Obtener todas las instancias de UsuarioTipoNorma asociadas al usuario
        $usuarioTipoNormas = $data['usuario']->getUsuarioTipoNormas();

        // Crear un array con los TipoNorma asociados
        $tipoNormas = array_map(function ($usuarioTipoNorma) {
            return $usuarioTipoNorma->getTipoNorma();
        }, $usuarioTipoNormas->toArray());

        if ($tipoNormas) {
            $data['form']->get('tipoNormas')->setData($tipoNormas); // Establece las normas seleccionadas en el formulario
        }

        $data['form']->handleRequest($request);
        $data['titulo'] = 'Editar usuario';
        $data['files_js'] = [
            'usuarios/usuarios.js',
        ];
        if ($data['form']->isSubmitted() && $data['form']->isValid()) {
            $newPassword = $data['form']->get('password')->getData();
            if ($newPassword) {
                $data['usuario']->setPassword($newPassword);
            }

            foreach ($data['usuario']->getUsuarioTipoNormas() as $usuarioTipoNorma) {
                $em->remove($usuarioTipoNorma);
            }

            $dataForm = $data['form']->getData();

            $rol = $dataForm->getRol()->getNombre();
            if ($rol === 'ROLE_ADMIN') {
                $selectedTipoNormas = $tipoNormaRepository->findBy(['isActive' => true]);
            } else {
                $selectedTipoNormas = $data['form']->get('tipoNormas')->getData();
            }
            foreach ($selectedTipoNormas as $tipoNorma) {
                $usuarioTipoNorma = new UsuarioTipoNorma();
                $usuarioTipoNorma->setUser($data['usuario'])
                    ->setTipoNorma($tipoNorma);
                $em->persist($usuarioTipoNorma);
            }

            $em->persist($data['usuario']);
            $em->flush();

            return $this->redirectToRoute('app_usuarios_index');
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
