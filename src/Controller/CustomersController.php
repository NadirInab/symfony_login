<?php

namespace App\Controller;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/api/customers", name: "app_user")]
class CustomersController extends AbstractController
{
    private $em ;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em ;
    }

    #[Route('/', name: 'app_customers')]
    public function index(): Response
    {
        $data = $this->em->getRepository(Customer::class)->findAll() ;
        $customers = [] ;
        foreach($data as $datum){
            $customers[] = [
                'email' => $datum->getEmail(),
                'role' => $datum->getRoles() ,
                'password' => $datum->getPassword()
            ] ;
        }
        return new JsonResponse([
            "customers" => $customers
        ]) ;
    }

    #[Route('/store', name: 'app_store')]
    public function store(Request $request): JsonResponse
    {
        // $data = $request->request->all() ;
        $customer = new Customer ;
        $customer->setEmail($request->request->get('email')) ;
        $customer->setRoles([$request->request->get('role')]) ;
        $customer->setPassword($request->request->get('password')) ;
        $this->em->persist($customer);
        $this->em->flush();

        return new JsonResponse([
            "data" => $customer
        ]) ;
    }
}
