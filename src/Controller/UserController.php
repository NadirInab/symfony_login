<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(path: "/api/users", name: "app_user")]
class UserController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $users = $em->getRepository(User::class)->findAll();
        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'age' => $user->getAge(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword()
            ];
        }
        return $this->json([
            'users' => $data
        ]);
    }

    #[Route('/store', name: 'user_store', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = new User;
        $user->setName($request->request->get('name'));
        $user->setEmail($request->request->get('email'));
        $user->setPassword($request->request->get('password'));
        $user->setAge($request->request->get('age'));
        $em->persist($user);
        $em->flush();
        $data = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'age' => $user->getAge()
        ];
        return new JsonResponse([
            "user" => $data
        ]);
    }

    #[Route('/{id}', name: "user_show", methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $em): JsonResponse
    {
        $user = $em->getRepository(User::class)->find($id);
        if (!$user) {
            return $this->json("No user is found for id: " . $id, 404);
        }
        $data = [
            "id" => $user->getId(),
            "name" => $user->getEmail(),
            "age" => $user->getAge(),
            "password" => $user->getPassword()
        ];

        return new JsonResponse([
            "user" => $data
        ]);
    }


    #[Route('/{id}', name: 'edit_user', methods: ['PUT'])]
    public function edit(Request $request, int $id, EntityManagerInterface $em): JsonResponse
    {
        $user = $em->getRepository(User::class)->find($id);
        if (!$user) {
            return $this->json("No User found for id :" . $id, 404);
        }
        $user->setName($request->request->get('name'));
        $user->setEmail($request->request->get('email'));
        $user->setAge($request->request->get('age'));
        $user->setPassword($request->request->get('password'));
        $em->flush();
        $data = [
            'id' => $user->getId(), 
            'name' => $user->getName(),
            'email' => $user->getEmail(), 
            'password' => $user->getPassword()
        ] ;

        return new JsonResponse([
            "user" => $data
        ]);
    }

    #[Route('/{id}', name: 'user_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): JsonResponse
    {
        $user = $em->getRepository(User::class)->find($id);
        if (!$user) {
            return $this->json("No user found for id :" . $id, 404);
        };
        $em->remove($user);
        $em->flush();
        return new JsonResponse([
            "user" => $user
        ]);
    }
}
