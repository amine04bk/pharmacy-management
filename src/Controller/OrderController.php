<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\MedicineRepository;
use App\Repository\OrderRepository;
use App\Repository\SupplierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/orders')]
class OrderController extends AbstractController
{
    #[Route('/', name: 'order_index')]
    public function index(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findBy([], ['orderDate' => 'DESC']);

        return $this->render('order/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/{id}', name: 'order_show', requirements: ['id' => '\d+'])]
    public function show(int $id, OrderRepository $orderRepository): Response
    {
        $order = $orderRepository->find($id);
        
        if (!$order) {
            throw $this->createNotFoundException('Order not found');
        }

        return $this->render('order/show.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/new', name: 'order_new')]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        MedicineRepository $medicineRepository,
        SupplierRepository $supplierRepository
    ): Response {
        if ($request->isMethod('POST')) {
            $medicineId = $request->request->get('medicine_id');
            $supplierId = $request->request->get('supplier_id');
            $quantity = $request->request->get('quantity');

            $medicine = $medicineRepository->find($medicineId);
            $supplier = $supplierRepository->find($supplierId);

            if (!$medicine || !$supplier) {
                $this->addFlash('error', 'Invalid medicine or supplier');
                return $this->redirectToRoute('order_new');
            }

            $order = new Order();
            $order->setMedicine($medicine);
            $order->setSupplier($supplier);
            $order->setQuantity((int)$quantity);
            $order->setStatus('pending');

            $entityManager->persist($order);
            $entityManager->flush();

            $this->addFlash('success', 'Order created successfully');
            return $this->redirectToRoute('order_index');
        }

        $medicines = $medicineRepository->findAll();
        $suppliers = $supplierRepository->findAll();

        return $this->render('order/new.html.twig', [
            'medicines' => $medicines,
            'suppliers' => $suppliers,
        ]);
    }

    #[Route('/{id}/update-status', name: 'order_update_status', methods: ['POST'])]
    public function updateStatus(
        int $id,
        Request $request,
        OrderRepository $orderRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $order = $orderRepository->find($id);
        
        if (!$order) {
            throw $this->createNotFoundException('Order not found');
        }

        $status = $request->request->get('status');
        $order->setStatus($status);
        $entityManager->flush();

        $this->addFlash('success', 'Order status updated successfully');
        
        return $this->redirectToRoute('order_show', ['id' => $id]);
    }

    #[Route('/{id}/complete', name: 'order_complete', methods: ['POST'])]
    public function complete(
        int $id,
        OrderRepository $orderRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $order = $orderRepository->find($id);
        
        if (!$order) {
            throw $this->createNotFoundException('Order not found');
        }

        $order->setStatus('completed');
        $entityManager->flush();

        $this->addFlash('success', 'Order marked as completed');
        
        return $this->redirectToRoute('order_index');
    }

    #[Route('/{id}/cancel', name: 'order_cancel', methods: ['POST'])]
    public function cancel(
        int $id,
        OrderRepository $orderRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $order = $orderRepository->find($id);
        
        if (!$order) {
            throw $this->createNotFoundException('Order not found');
        }

        $order->setStatus('cancelled');
        $entityManager->flush();

        $this->addFlash('warning', 'Order has been cancelled');
        
        return $this->redirectToRoute('order_index');
    }
}
