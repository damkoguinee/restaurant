<?php

namespace App\Controller\Nhema\Admin;

use App\Entity\Cocktail;
use App\Form\CocktailType;
use App\Repository\RecetteRepository;
use App\Repository\CocktailRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LieuxVentesRepository;
use App\Repository\CocktailRecetteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/nhema/admin/cocktail')]
class CocktailController extends AbstractController
{
    #[Route('/', name: 'app_nhema_admin_cocktail_index', methods: ['GET'])]
    public function index(Request $request, CocktailRepository $cocktailRepository, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        if ($request->get("search")){
            $search = $request->get("search");
        }else{
            $search = "";
        }
        $pageEncours = $request->get('pageEncours', 1);
        $listecocktails = $cocktailRepository->listeDescocktailsPagination($search, $pageEncours, 15); 
        return $this->render('nhema/admin/cocktail/index.html.twig', [
            'cocktails' => $listecocktails,
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
            'search' => $search
        ]);
    }

    #[Route('/new', name: 'app_nhema_admin_cocktail_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        $cocktail = new cocktail();
        $form = $this->createForm(CocktailType::class, $cocktail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fichier = $form->get("image")->getData();
            if ($fichier) {
                $nomFichier= pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                $slugger = new AsciiSlugger();
                $nouveauNomFichier = $slugger->slug($nomFichier);
                $nouveauNomFichier .="_".uniqid();
                $nouveauNomFichier .= "." .$fichier->guessExtension();
                $fichier->move($this->getParameter("dossier_img_cocktails"),$nouveauNomFichier);
                $cocktail->setImage($nouveauNomFichier);
            }
            $cocktail->setLieuVente($this->getUser()->getLieuVente());
            $entityManager->persist($cocktail);
            $entityManager->flush();
            $this->addFlash("success","cocktail ajouté avec succès :)");
            return $this->redirectToRoute('app_nhema_admin_cocktail_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/cocktail/new.html.twig', [
            'cocktail' => $cocktail,
            'form' => $form,
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_nhema_admin_cocktail_show', methods: ['GET'])]
    public function show(Cocktail $cocktail, RecetteRepository $recetteRep, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        return $this->render('nhema/admin/cocktail/show.html.twig', [
            'cocktail' => $cocktail,
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
            'recettes' => $recetteRep->findBy(['product' => $cocktail]),

        ]);
    }

    #[Route('/edit/{id}', name: 'app_nhema_admin_cocktail_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cocktail $cocktail, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        $form = $this->createForm(CocktailType::class, $cocktail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image =$form->get("image")->getData();
            if ($image) {
                if ($cocktail->getImage()) {
                    $ancienImage=$this->getParameter("dossier_img_cocktails")."/".$cocktail->getImage();
                    if (file_exists($ancienImage)) {
                        unlink($ancienImage);
                    }
                }
                $nomImage= pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $slugger = new AsciiSlugger();
                $nouveauNomImage = $slugger->slug($nomImage);
                $nouveauNomImage .="_".uniqid();
                $nouveauNomImage .= "." .$image->guessExtension();
                $image->move($this->getParameter("dossier_img_cocktails"),$nouveauNomImage);
                $cocktail->setImage($nouveauNomImage);

            }
            $entityManager->flush();
            $this->addFlash("success","cocktail amodifié avec succès :)");
            return $this->redirectToRoute('app_nhema_admin_cocktail_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/cocktail/edit.html.twig', [
            'cocktail' => $cocktail,
            'form' => $form,
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_nhema_admin_cocktail_delete', methods: ['POST'])]
    public function delete(Request $request, Cocktail $cocktail, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cocktail->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cocktail);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_nhema_admin_cocktail_index', [], Response::HTTP_SEE_OTHER);
    }
}
