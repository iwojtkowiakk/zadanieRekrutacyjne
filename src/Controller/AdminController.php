<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Warehouse;
use App\Form\RegistrationFormType;
use App\Form\WarehouseAddFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route("/admin/add-user", "admin_add_user", methods: ["GET", "POST"])]
    public function addUser(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User();

            $user->setUsername($form->get('username')->getData());

            $plaintextPassword = $form->get('password')->getData();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );

            $user->setPassword($hashedPassword);

            $warehouses = $form->get('warehouses')->getData();
            foreach ($warehouses as $warehouse) {
                $user->addWarehouse($warehouse);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            if (!empty($user->getId())) {
                $this->addFlash("success", "Dodano użytkownika");
            }

            return $this->redirectToRoute('admin_add_user');
        }

        return $this->render('admin/add_user.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/admin/add-warehouse", "admin_add_warehouse", methods: ["GET", "POST"])]
    public function addWarehouse(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WarehouseAddFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $warehouse = new Warehouse();
            $warehouse->setName($form->get("name")->getData());

            $users = $form->get("users")->getData();
            foreach ($users as $user) {
                $warehouse->addUser($user);
            }

            $entityManager->persist($warehouse);
            $entityManager->flush();

            if (!empty($warehouse->getId())) {
                $this->addFlash("success", "Dodano magazyn");
            }

            return $this->redirectToRoute('admin_add_warehouse');
        }

        return $this->render('admin/add_warehouse.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}