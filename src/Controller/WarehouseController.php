<?php

namespace App\Controller;

use App\Entity\Warehouse;
use App\Form\RegistrationFormType;
use App\Form\WarehouseAddFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WarehouseController extends AbstractController
{
    #[Route("/", "warehouse_home")]
    public function home(): Response
    {
        return $this->render('warehouse/home.html.twig');
    }

    #[Route("/admin/add-warehouse", "admin_add_warehouse", methods: ["GET", "POST"])]
    public function addWarehouse(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WarehouseAddFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $warehouse = new Warehouse();
            $warehouse->setName($form->get("name")->getData());
        }
        return $this->render('admin/add_warehouse.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}