<?php
namespace App\Controller;

use App\Entity\User;
use App\Repository\MedicineRepository;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        UserRepository $userRepository,
        MedicineRepository $medicineRepository,
        OrderRepository $orderRepository
    ): Response {
        $user = $this->getUser();
        
        // Redirect based on role
        if ($user && in_array(User::ROLE_PHARMACY, $user->getRoles(), true)) {
            return $this->redirectToRoute('pharmacy_dashboard');
        }
        
        if ($user && in_array(User::ROLE_SUPPLIER, $user->getRoles(), true)) {
            return $this->redirectToRoute('supplier_dashboard');
        }

        // Admin dashboard statistics
        $stats = [
            'total_users' => count($userRepository->findAll()),
            'total_pharmacies' => $userRepository->countByRole(User::ROLE_PHARMACY),
            'total_suppliers' => $userRepository->countByRole(User::ROLE_SUPPLIER),
            'total_delivery' => $userRepository->countByRole(User::ROLE_DELIVERY),
            'total_medicines' => count($medicineRepository->findAll()),
            'low_stock_medicines' => count($medicineRepository->findLowStock(10)),
            'expiring_soon' => count($medicineRepository->findExpiringSoon(30)),
            'pending_orders' => $orderRepository->countByStatus('pending'),
            'total_inventory_value' => $medicineRepository->getTotalValue(),
        ];

        $recentOrders = $orderRepository->findRecentOrders(10);
        $lowStockMedicines = $medicineRepository->findLowStock(10);

        return $this->render('home/index.html.twig', [
            'stats' => $stats,
            'recent_orders' => $recentOrders,
            'low_stock_medicines' => $lowStockMedicines,
        ]);
    }
}
