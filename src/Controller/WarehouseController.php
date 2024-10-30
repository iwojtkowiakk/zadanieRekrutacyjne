<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Transaction;
use App\Entity\Warehouse;
use App\Form\ProductAddFormType;
use App\Form\RegistrationFormType;
use App\Form\TransactionFormType;
use App\Form\WarehouseAddFormType;
use App\Repository\TransactionRepository;
use App\Repository\WarehouseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WarehouseController extends AbstractController
{

    #[Route('/warehouse/', name: 'warehouse_all')]
    public function listWarehouses(WarehouseRepository $warehouseRepository): Response
    {
        $warehouses = $warehouseRepository->findAll();

        return $this->render('warehouse/warehouses.html.twig', [
            'warehouses' => $warehouses,
        ]);
    }
    #[Route('/warehouse/{id}/list', name: 'warehouse_list')]
    public function listProducts(Warehouse $warehouse, TransactionRepository $transactionRepository): Response
    {
        $products = $transactionRepository->findProductsInWarehouse($warehouse);

        return $this->render('warehouse/list.html.twig', [
            'warehouse' => $warehouse,
            'products' => $products,
        ]);
    }

    #[Route("/warehouse/transaction", "warehouse_transaction")]
    public function transaction(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TransactionFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $transaction = new Transaction();
            $transaction->setTransactionType($form->get("transactionType")->getData());
            $transaction->setWarehouse($form->get("warehouse")->getData());
            $transaction->setProduct($form->get("product")->getData());
            $transaction->setQuantity($form->get("quantity")->getData());
            $transaction->setPrice($form->get("price")->getData());

            $entityManager->persist($transaction);
            $entityManager->flush();

            $this->addFlash('success', 'Transaction created successfully!');
        }

        return $this->render('warehouse/transaction.html.twig', [
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
                $this->addFlash("success", "Warehouse added successfully");
            }
        }

        return $this->render('admin/add_warehouse.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/admin/add-product", "admin_add_product", methods: ["GET", "POST"])]
    public function addProduct(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductAddFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = new Product();
            $product->setName($form->get("name")->getData());
            $product->setUnit($form->get("unit")->getData());

            $entityManager->persist($product);
            $entityManager->flush();

            if (!empty($product->getId())) {
                $this->addFlash("success", "Product added successfully");
            }
        }

        return $this->render('admin/add_product.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}