<?php

namespace App\Controller;

use App\Entity\Medicine;
use App\Repository\MedicineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/medicines')]
class MedicineController extends AbstractController
{
    #[Route('/', name: 'medicine_index')]
    public function index(MedicineRepository $medicineRepository): Response
    {
        $medicines = $medicineRepository->findBy([], ['name' => 'ASC']);

        return $this->render('medicine/index.html.twig', [
            'medicines' => $medicines,
        ]);
    }

    #[Route('/{id}', name: 'medicine_show', requirements: ['id' => '\d+'])]
    public function show(int $id, MedicineRepository $medicineRepository): Response
    {
        $medicine = $medicineRepository->find($id);
        
        if (!$medicine) {
            throw $this->createNotFoundException('Medicine not found');
        }

        return $this->render('medicine/show.html.twig', [
            'medicine' => $medicine,
        ]);
    }

    #[Route('/new', name: 'medicine_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $medicine = new Medicine();
            $medicine->setName($request->request->get('name'));
            $medicine->setDescription($request->request->get('description'));
            $medicine->setCategory($request->request->get('category'));
            $medicine->setQuantity((int)$request->request->get('quantity'));
            $medicine->setPrice($request->request->get('price'));
            
            $expirationDate = $request->request->get('expiration_date');
            if ($expirationDate) {
                $medicine->setExpirationDate(new \DateTime($expirationDate));
            }

            $entityManager->persist($medicine);
            $entityManager->flush();

            $this->addFlash('success', 'Medicine created successfully');
            return $this->redirectToRoute('medicine_index');
        }

        return $this->render('medicine/new.html.twig');
    }

    #[Route('/{id}/edit', name: 'medicine_edit')]
    public function edit(int $id, Request $request, MedicineRepository $medicineRepository, EntityManagerInterface $entityManager): Response
    {
        $medicine = $medicineRepository->find($id);
        
        if (!$medicine) {
            throw $this->createNotFoundException('Medicine not found');
        }

        if ($request->isMethod('POST')) {
            $medicine->setName($request->request->get('name'));
            $medicine->setDescription($request->request->get('description'));
            $medicine->setCategory($request->request->get('category'));
            $medicine->setQuantity((int)$request->request->get('quantity'));
            $medicine->setPrice($request->request->get('price'));
            
            $expirationDate = $request->request->get('expiration_date');
            if ($expirationDate) {
                $medicine->setExpirationDate(new \DateTime($expirationDate));
            }

            $entityManager->flush();

            $this->addFlash('success', 'Medicine updated successfully');
            return $this->redirectToRoute('medicine_index');
        }

        return $this->render('medicine/edit.html.twig', [
            'medicine' => $medicine,
        ]);
    }

    #[Route('/{id}/delete', name: 'medicine_delete', methods: ['POST'])]
    public function delete(int $id, MedicineRepository $medicineRepository, EntityManagerInterface $entityManager): Response
    {
        $medicine = $medicineRepository->find($id);
        
        if (!$medicine) {
            throw $this->createNotFoundException('Medicine not found');
        }

        $entityManager->remove($medicine);
        $entityManager->flush();

        $this->addFlash('success', 'Medicine deleted successfully');
        
        return $this->redirectToRoute('medicine_index');
    }
}
