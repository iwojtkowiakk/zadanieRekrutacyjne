<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Transaction;
use App\Entity\Warehouse;
use App\Enum\TransactionType;
use App\Form\ProductAddFormType;
use App\Form\TransactionFormType;
use App\Repository\TransactionRepository;
use App\Repository\WarehouseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class WarehouseController extends AbstractController
{
    #[Route('/warehouse', name: 'warehouse_all')]
    public function listWarehouses(WarehouseRepository $warehouseRepository): Response
    {
        $user = $this->getUser();

        if ($this->isGranted('ROLE_ADMIN')) {
            $warehouses = $warehouseRepository->findAll();
        } else {
            $warehouses = $user->getWarehouses();
        }

        return $this->render('warehouse/warehouses.html.twig', [
            'warehouses' => $warehouses,
        ]);
    }

    #[Route('/warehouse/{id}', name: 'warehouse_list')]
    public function listProducts(?Warehouse $warehouse, TransactionRepository $transactionRepository, Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager, #[Autowire('%kernel.project_dir/public/uploads/files')] string $filesDirectory): Response
    {
        $user = $this->getUser();

        if (!$warehouse) {
            $this->addFlash("danger", "Nie znaleziono magazynu");
            return $this->redirectToRoute('warehouse_all');
        }

        if (!$this->isGranted('ROLE_ADMIN') && !$user->getWarehouses()->contains($warehouse)) {
            $this->addFlash("danger", "Nie masz dostępu do tego magazynu.");
            return $this->redirectToRoute('warehouse_all');
        }

        $form = $this->createForm(TransactionFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $transaction = new Transaction();
            $transactionType = $form->get("transactionType")->getData();
            $transaction->setTransactionType($transactionType);
            $transaction->setWarehouse($warehouse);
            $transaction->setProduct($form->get("product")->getData());
            $transaction->setQuantity($form->get("quantity")->getData());

            if ($transactionType === TransactionType::IN) {
                $transaction->setPrice($form->get("price")->getData());
                $transaction->setVat($form->get("vat")->getData());
                $file = $form->get("file")->getData();

                if ($file) {
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                    try {
                        $file->move($filesDirectory, $newFilename);
                    } catch (FileException $e) {

                    }

                    $transaction->setFile($newFilename);
                }
            }

            $entityManager->persist($transaction);
            $entityManager->flush();

            if(!empty($transaction->getId())) {
                $this->addFlash('success', 'Wykonano transkację');
            }

            return $this->redirectToRoute('warehouse_list', ['id' => $warehouse->getId()]);
        }

        $products = $transactionRepository->findProductsInWarehouse($warehouse);
        $transactions = $transactionRepository->findAllByWarehouse($warehouse);

        return $this->render('warehouse/list.html.twig', [
            'form' => $form->createView(),
            'warehouse' => $warehouse,
            'products' => $products,
            'transactions' => $transactions,
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
                $this->addFlash("success", "Dodano produkt");
            }

            return $this->redirectToRoute('admin_add_product');
        }

        return $this->render('admin/add_product.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}