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
    #[Route('/', name: 'index', methods:['GET'])]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    #[Route('/store', name:'store', methods:['POST'])]
    public function store(Request $request, EntityManagerInterface $em): JsonResponse
    {   
        $data = $request->request->all() ;

        $name = $request->request->get('name') ;
        $email = $request->request->get('email') ;
        $password = $request->request->get('password') ;
        $age = $request->request->get('age') ;
        $user = new User ;
        $user->setName($name) ;
        $user->setEmail($email) ;
        $user->setPassword($password) ;
        $user->setAge($age) ;
        $em->persist($user) ;
        $em->flush() ;
        return new JsonResponse([
            "user" => $user
        ]) ;
    }
}
