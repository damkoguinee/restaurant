<?php

namespace App\Controller\Nhema\Admin;

use App\Entity\Dessert;
use App\Form\DessertType;
use App\Repository\DessertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/nhema/admin/dessert')]
class DessertController extends AbstractController
{
    #[Route('/', name: 'app_nhema_admin_dessert_index', methods: ['GET'])]
    public function index(DessertRepository $dessertRepository): Response
    {
        return $this->render('nhema/admin/dessert/index.html.twig', [
            'desserts' => $dessertRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_nhema_admin_dessert_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dessert = new Dessert();
        $form = $this->createForm(DessertType::class, $dessert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($dessert);
            $entityManager->flush();

            return $this->redirectToRoute('app_nhema_admin_dessert_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/dessert/new.html.twig', [
            'dessert' => $dessert,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_nhema_admin_dessert_show', methods: ['GET'])]
    public function show(Dessert $dessert): Response
    {
        return $this->render('nhema/admin/dessert/show.html.twig', [
            'dessert' => $dessert,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_nhema_admin_dessert_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Dessert $dessert, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DessertType::class, $dessert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_nhema_admin_dessert_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/dessert/edit.html.twig', [
            'dessert' => $dessert,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_nhema_admin_dessert_delete', methods: ['POST'])]
    public function delete(Request $request, Dessert $dessert, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dessert->getId(), $request->request->get('_token'))) {
            $entityManager->remove($dessert);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_nhema_admin_dessert_index', [], Response::HTTP_SEE_OTHER);
    }
}
