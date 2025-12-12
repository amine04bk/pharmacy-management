<?php

namespace App\Controller;

use App\Entity\Medicine;
use App\Entity\SupplierInventory;
use App\Repository\MedicineRepository;
use App\Repository\OrderRepository;
use App\Repository\SupplierInventoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/supplier')]
class SupplierPortalController extends AbstractController
{
    #[Route('/dashboard', name: 'supplier_dashboard')]
    public function dashboard(
        SupplierInventoryRepository $inventoryRepository,
        OrderRepository $orderRepository
    ): Response {
        $user = $this->getUser();
        $supplier = $user->getSupplier();
        
        if (!$supplier) {
            throw $this->createNotFoundException('Supplier not found for this user');
        }
        
        $inventory = $inventoryRepository->findBySupplier($supplier->getId());
        $lowStock = $inventoryRepository->findLowStockBySupplier($supplier->getId(), 50);
        $totalValue = $inventoryRepository->getTotalStockValueBySupplier($supplier->getId());
        $orders = $orderRepository->findBySupplier($supplier->getId());

        return $this->render('supplier_portal/dashboard.html.twig', [
            'inventory_count' => count($inventory),
            'low_stock_count' => count($lowStock),
            'total_value' => $totalValue,
            'orders' => array_slice($orders, 0, 10),
        ]);
    }

    #[Route('/medicines', name: 'supplier_medicines')]
    public function medicines(SupplierInventoryRepository $inventoryRepository): Response
    {
        $user = $this->getUser();
        $supplier = $user->getSupplier();
        
        if (!$supplier) {
            throw $this->createNotFoundException('Supplier not found for this user');
        }
        
        $inventory = $inventoryRepository->findBySupplier($supplier->getId());

        return $this->render('supplier_portal/medicines.html.twig', [
            'inventory' => $inventory,
        ]);
    }

    #[Route('/medicines/add', name: 'supplier_add_medicine')]
    public function addMedicine(
        Request $request,
        MedicineRepository $medicineRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
        $supplier = $user->getSupplier();
        
        if (!$supplier) {
            throw $this->createNotFoundException('Supplier not found for this user');
        }

        if ($request->isMethod('POST')) {
            $medicineId = $request->request->get('medicine_id');
            $quantity = $request->request->get('quantity');
            $wholesalePrice = $request->request->get('wholesale_price');

            $medicine = $medicineRepository->find($medicineId);
            
            if (!$medicine) {
                $this->addFlash('error', 'Medicine not found');
                return $this->redirectToRoute('supplier_add_medicine');
            }

            $inventory = new SupplierInventory();
            $inventory->setSupplier($supplier);
            $inventory->setMedicine($medicine);
            $inventory->setQuantity((int)$quantity);
            $inventory->setWholesalePrice($wholesalePrice);

            $entityManager->persist($inventory);
            $entityManager->flush();

            $this->addFlash('success', 'Medicine added to inventory');
            return $this->redirectToRoute('supplier_medicines');
        }

        $medicines = $medicineRepository->findAll();

        return $this->render('supplier_portal/add_medicine.html.twig', [
            'medicines' => $medicines,
        ]);
    }

    #[Route('/orders', name: 'supplier_orders')]
    public function orders(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();
        $supplier = $user->getSupplier();
        
        if (!$supplier) {
            throw $this->createNotFoundException('Supplier not found for this user');
        }
        
        $orders = $orderRepository->findBySupplier($supplier->getId());

        return $this->render('supplier_portal/orders.html.twig', [
            'orders' => $orders,
        ]);
    }
}
