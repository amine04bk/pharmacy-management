<?php

namespace App\Controller;

use App\Entity\Supplier;
use App\Repository\SupplierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/suppliers')]
class SupplierController extends AbstractController
{
    #[Route('/', name: 'supplier_index')]
    public function index(SupplierRepository $supplierRepository): Response
    {
        $suppliers = $supplierRepository->findAllOrdered();

        return $this->render('supplier/index.html.twig', [
            'suppliers' => $suppliers,
        ]);
    }

    #[Route('/new', name: 'supplier_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $supplier = new Supplier();
            $supplier->setName($request->request->get('name'));
            $supplier->setEmail($request->request->get('email'));
            $supplier->setPhone($request->request->get('phone'));
            $supplier->setAddress($request->request->get('address'));

            $entityManager->persist($supplier);
            $entityManager->flush();

            $this->addFlash('success', 'Supplier created successfully');
            return $this->redirectToRoute('supplier_index');
        }

        return $this->render('supplier/new.html.twig');
    }

    #[Route('/{id}/edit', name: 'supplier_edit')]
    public function edit(int $id, Request $request, SupplierRepository $supplierRepository, EntityManagerInterface $entityManager): Response
    {
        $supplier = $supplierRepository->find($id);
        
        if (!$supplier) {
            throw $this->createNotFoundException('Supplier not found');
        }

        if ($request->isMethod('POST')) {
            $supplier->setName($request->request->get('name'));
            $supplier->setEmail($request->request->get('email'));
            $supplier->setPhone($request->request->get('phone'));
            $supplier->setAddress($request->request->get('address'));

            $entityManager->flush();

            $this->addFlash('success', 'Supplier updated successfully');
            return $this->redirectToRoute('supplier_index');
        }

        return $this->render('supplier/edit.html.twig', [
            'supplier' => $supplier,
        ]);
    }

    #[Route('/{id}/delete', name: 'supplier_delete', methods: ['POST'])]
    public function delete(int $id, SupplierRepository $supplierRepository, EntityManagerInterface $entityManager): Response
    {
        $supplier = $supplierRepository->find($id);
        
        if (!$supplier) {
            throw $this->createNotFoundException('Supplier not found');
        }

        $entityManager->remove($supplier);
        $entityManager->flush();

        $this->addFlash('success', 'Supplier deleted successfully');
        
        return $this->redirectToRoute('supplier_index');
    }
}
