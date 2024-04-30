<?php

namespace App\Controller\Nhema\Admin;

use App\Entity\CategorieDepense;
use App\Entity\LieuxVentes;
use App\Form\CategorieDepenseType;
use App\Repository\CategorieDepenseRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/nhema/admin/categorie/depense')]
class CategorieDepenseController extends AbstractController
{
    #[Route('/', name: 'app_nhema_admin_categorie_depense_index', methods: ['GET'])]
    public function index(CategorieDepenseRepository $categorieDepenseRepository): Response
    {
        return $this->render('nhema/admin/categorie_depense/index.html.twig', [
            'categorie_depenses' => $categorieDepenseRepository->findAll(),
        ]);
    }

    #[Route('/new/{lieu_vente}', name: 'app_nhema_admin_categorie_depense_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntrepriseRepository $entrepriseRep, LieuxVentes $lieu_vente, EntityManagerInterface $entityManager): Response
    {
        $categorieDepense = new CategorieDepense();
        $form = $this->createForm(CategorieDepenseType::class, $categorieDepense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorieDepense);
            $entityManager->flush();
            $this->addFlash("success", "Catégorie ajoutée avec succès :)");
            return $this->redirectToRoute('app_nhema_sorties_depenses_new', ['lieu_vente' => $lieu_vente->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/categorie_depense/new.html.twig', [
            'categorie_depense' => $categorieDepense,
            'form' => $form,
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
        ]);
    }

    #[Route('/{id}', name: 'app_nhema_admin_categorie_depense_show', methods: ['GET'])]
    public function show(CategorieDepense $categorieDepense): Response
    {
        return $this->render('nhema/admin/categorie_depense/show.html.twig', [
            'categorie_depense' => $categorieDepense,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_nhema_admin_categorie_depense_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategorieDepense $categorieDepense, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieDepenseType::class, $categorieDepense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_nhema_admin_categorie_depense_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/categorie_depense/edit.html.twig', [
            'categorie_depense' => $categorieDepense,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_nhema_admin_categorie_depense_delete', methods: ['POST'])]
    public function delete(Request $request, CategorieDepense $categorieDepense, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorieDepense->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categorieDepense);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_nhema_admin_categorie_depense_index', [], Response::HTTP_SEE_OTHER);
    }
}
