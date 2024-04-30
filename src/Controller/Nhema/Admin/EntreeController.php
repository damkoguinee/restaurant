<?php

namespace App\Controller\Nhema\Admin;

use App\Entity\Entree;
use App\Form\EntreeType;
use App\Repository\EntreeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/nhema/admin/entree')]
class EntreeController extends AbstractController
{
    #[Route('/', name: 'app_nhema_admin_entree_index', methods: ['GET'])]
    public function index(EntreeRepository $entreeRepository): Response
    {
        return $this->render('nhema/admin/entree/index.html.twig', [
            'entrees' => $entreeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_nhema_admin_entree_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $entree = new Entree();
        $form = $this->createForm(EntreeType::class, $entree);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entree);
            $entityManager->flush();

            return $this->redirectToRoute('app_nhema_admin_entree_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/entree/new.html.twig', [
            'entree' => $entree,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_nhema_admin_entree_show', methods: ['GET'])]
    public function show(Entree $entree): Response
    {
        return $this->render('nhema/admin/entree/show.html.twig', [
            'entree' => $entree,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_nhema_admin_entree_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Entree $entree, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EntreeType::class, $entree);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_nhema_admin_entree_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/entree/edit.html.twig', [
            'entree' => $entree,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_nhema_admin_entree_delete', methods: ['POST'])]
    public function delete(Request $request, Entree $entree, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entree->getId(), $request->request->get('_token'))) {
            $entityManager->remove($entree);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_nhema_admin_entree_index', [], Response::HTTP_SEE_OTHER);
    }
}
