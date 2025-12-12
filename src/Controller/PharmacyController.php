<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PharmacyInventoryRepository;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pharmacy')]
class PharmacyController extends AbstractController
{
    #[Route('/dashboard', name: 'pharmacy_dashboard')]
    public function dashboard(
        PharmacyInventoryRepository $inventoryRepository,
        OrderRepository $orderRepository
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        
        $inventory = $inventoryRepository->findByPharmacy($user->getId());
        $lowStock = $inventoryRepository->findLowStockByPharmacy($user->getId(), 10);
        $totalValue = $inventoryRepository->getTotalStockValueByPharmacy($user->getId());
        $orders = $orderRepository->findRecentOrders(10);

        return $this->render('pharmacy/dashboard.html.twig', [
            'inventory_count' => count($inventory),
            'low_stock_count' => count($lowStock),
            'total_value' => $totalValue,
            'recent_orders' => $orders,
        ]);
    }

    #[Route('/stock', name: 'pharmacy_stock')]
    public function stock(PharmacyInventoryRepository $inventoryRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        
        $inventory = $inventoryRepository->findByPharmacy($user->getId());

        return $this->render('pharmacy/stock.html.twig', [
            'inventory' => $inventory,
        ]);
    }

    #[Route('/orders', name: 'pharmacy_orders')]
    public function orders(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findRecentOrders(50);

        return $this->render('pharmacy/orders.html.twig', [
            'orders' => $orders,
        ]);
    }
}
