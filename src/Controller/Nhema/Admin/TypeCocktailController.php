<?php

namespace App\Controller\Nhema\Admin;

use App\Entity\TypeCocktail;
use App\Form\TypeCocktailType;
use App\Repository\TypeCocktailRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LieuxVentesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/nhema/admin/type/cocktail')]
class TypeCocktailController extends AbstractController
{
    #[Route('/', name: 'app_nhema_admin_type_cocktail_index', methods: ['GET'])]
    public function index(Request $request, TypeCocktailRepository $type_cocktailRepository, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        if ($request->get("search")){
            $search = $request->get("search");
        }else{
            $search = "";
        }
        $pageEncours = $request->get('pageEncours', 1);
        $liste_type_cocktails = $type_cocktailRepository->listeDesTypeCocktailsPagination($search, $pageEncours, 15); 

        return $this->render('nhema/admin/type_cocktail/index.html.twig', [
            'type_cocktails' => $liste_type_cocktails,
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
            'search' => $search
        ]);
    }

    #[Route('/new', name: 'app_nhema_admin_type_cocktail_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        $type_cocktail = new TypeCocktail();
        $form = $this->createForm(TypeCocktailType::class, $type_cocktail);
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
                $type_cocktail->setImage($nouveauNomFichier);
            }
            $entityManager->persist($type_cocktail);
            $entityManager->flush();
            $this->addFlash("success","le type de cocktail ajoutée avec succès :)");
            return $this->redirectToRoute('app_nhema_admin_type_cocktail_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/type_cocktail/new.html.twig', [
            'type_cocktail' => $type_cocktail,
            'form' => $form,
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_nhema_admin_type_cocktail_show', methods: ['GET'])]
    public function show(TypeCocktail $type_cocktail, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        return $this->render('nhema/admin/type_cocktail/show.html.twig', [
            'type_cocktail' => $type_cocktail,
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
        ]);
    }

    #[Route('/edit/{id}', name: 'app_nhema_admin_type_cocktail_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeCocktail $type_cocktail, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        $form = $this->createForm(TypeCocktailType::class, $type_cocktail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image =$form->get("image")->getData();
            if ($image) {
                if ($type_cocktail->getImage()) {
                    $ancienImage=$this->getParameter("dossier_img_cocktails")."/".$type_cocktail->getImage();
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
                $type_cocktail->setImage($nouveauNomImage);

            }
            $entityManager->flush();
            $this->addFlash("success","le type de cocktail amodifiée avec succès :)");
            return $this->redirectToRoute('app_nhema_admin_type_cocktail_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/type_cocktail/edit.html.twig', [
            'type_cocktail' => $type_cocktail,
            'form' => $form,
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_nhema_admin_type_cocktail_delete', methods: ['POST'])]
    public function delete(Request $request, TypeCocktail $type_cocktail, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        if ($this->isCsrfTokenValid('delete'.$type_cocktail->getId(), $request->request->get('_token'))) {
            $entityManager->remove($type_cocktail);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_nhema_admin_type_cocktail_index', [], Response::HTTP_SEE_OTHER);
    }
}
