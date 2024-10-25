<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class RegistrationController extends AbstractController
{
    #[Route("/admin/add-user", "admin_add_user", methods: ["GET", "POST"])]
    public function new(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
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
            $user->setRoles([$form->get('roles')->getData()]);

            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('add_user.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function delete(UserPasswordHasherInterface $passwordHasher, UserInterface $user): void
    {
        // ... e.g. get the password from a "confirm deletion" dialog
        $plaintextPassword = "Kutas2137";

        if (!$passwordHasher->isPasswordValid($user, $plaintextPassword)) {
            throw new AccessDeniedHttpException();
        }
    }
}