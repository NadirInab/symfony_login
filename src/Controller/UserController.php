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
    #[Route('/user', name: 'app_user', methods:['GET'])]
    #[Route('/', name: 'index', methods:['GET'])]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    #[Route('/store', name:'user_store', methods:['POST'])]
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

    #[Route('/show/{id}', name:"user_show", methods:['GET'])]
    public function show(int $id, EntityManagerInterface $em): JsonResponse 
    {
        $user = $em->getRepository(User::class)->find($id);
        if(!$user){
            return $this->json("No user is found for id".$id, 404) ;
        }
        $data = [
            "id" => $user->getId() ,
            "name" => $user->getEmail(),
            "age" => $user->getAge(),
            "password" => $user->getPassword()
        ] ;

        return new JsonResponse([
            "user" => $data
        ]) ;
    }


    #[Route('edit/{id}', name:'edit_user', methods:['PUT'])]
    public function edit(Request $request,int $id, EntityManagerInterface $em): JsonResponse
    {
        $user = $em->getRepository(User::class)->find($id);
        if(!$user){
            return $this->json("No User found for id :".$id, 404) ;
        }
        $user->setName($request->request->get('name')) ;
        $user->setEmail($request->request->get('email')) ;
        $user->setAge($request->request->get('age')) ;
        $user->setPassword($request->request->get('password')) ;
        $em->flush() ;

        return new JsonResponse([
            "user" => $user
        ]) ;
    }

    #[Route('user/{id}', name:'user_delete', methods:['DELETE'])]
    public function delete(int $id,EntityManagerInterface $em): JsonResponse
    {
        $user = $em->getRepository(User::class)->find($id) ;
        if(!$user){
            return $this->json("No user found for id :".$id, 404) ;
        } ;
        $em->remove($user) ;
        $em->flush() ;
        return new JsonResponse([
            "user" => $user
        ]) ;
    }
}
