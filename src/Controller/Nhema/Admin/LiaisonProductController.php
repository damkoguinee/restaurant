<?php

namespace App\Controller\Nhema\Admin;

use App\Entity\LiaisonProduct;
use App\Form\LiaisonProductType;
use App\Repository\LiaisonProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/nhema/admin/liaison/product')]
class LiaisonProductController extends AbstractController
{
    #[Route('/', name: 'app_nhema_admin_liaison_product_index', methods: ['GET'])]
    public function index(LiaisonProductRepository $liaisonProductRepository): Response
    {
        return $this->render('nhema/admin/liaison_product/index.html.twig', [
            'liaison_products' => $liaisonProductRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_nhema_admin_liaison_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $liaisonProduct = new LiaisonProduct();
        $form = $this->createForm(LiaisonProductType::class, $liaisonProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($liaisonProduct);
            $entityManager->flush();

            return $this->redirectToRoute('app_nhema_admin_liaison_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/liaison_product/new.html.twig', [
            'liaison_product' => $liaisonProduct,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_nhema_admin_liaison_product_show', methods: ['GET'])]
    public function show(LiaisonProduct $liaisonProduct): Response
    {
        return $this->render('nhema/admin/liaison_product/show.html.twig', [
            'liaison_product' => $liaisonProduct,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_nhema_admin_liaison_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LiaisonProduct $liaisonProduct, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LiaisonProductType::class, $liaisonProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_nhema_admin_liaison_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/liaison_product/edit.html.twig', [
            'liaison_product' => $liaisonProduct,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_nhema_admin_liaison_product_delete', methods: ['POST', 'GET'])]
    public function delete(Request $request, LiaisonProduct $liaisonProduct, EntityManagerInterface $entityManager): Response
    {
        // if ($this->isCsrfTokenValid('delete'.$liaisonProduct->getId(), $request->request->get('_token'))) {
        //     $entityManager->remove($liaisonProduct);
        //     $entityManager->flush();
        // }
        $entityManager->remove($liaisonProduct);
        $entityManager->flush();

        return $this->redirectToRoute('app_nhema_admin_boisson_show', ['id' => $liaisonProduct->getProduct1()->getId()], Response::HTTP_SEE_OTHER);
    }
}
