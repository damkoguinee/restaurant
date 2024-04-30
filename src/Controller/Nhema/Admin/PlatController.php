<?php

namespace App\Controller\Nhema\Admin;

use App\Entity\Plat;
use App\Form\PlatType;
use App\Repository\PlatRepository;
use App\Repository\ProductRepository;
use App\Repository\RecetteRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LieuxVentesRepository;
use App\Repository\PlatRecetteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/nhema/admin/plat')]
class PlatController extends AbstractController
{
    #[Route('/', name: 'app_nhema_admin_plat_index', methods: ['GET'])]
    public function index(Request $request, PlatRepository $platRepository, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        if ($request->get("search")){
            $search = $request->get("search");
        }else{
            $search = "";
        }
        $pageEncours = $request->get('pageEncours', 1);
        $listePlats = $platRepository->listeDesPlatsPagination($search, $pageEncours, 15); 
        return $this->render('nhema/admin/plat/index.html.twig', [
            'plats' => $listePlats,
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
            'search' => $search
        ]);
    }

    #[Route('/new', name: 'app_nhema_admin_plat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        $plat = new plat();
        $form = $this->createForm(PlatType::class, $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fichier = $form->get("image")->getData();
            if ($fichier) {
                $nomFichier= pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                $slugger = new AsciiSlugger();
                $nouveauNomFichier = $slugger->slug($nomFichier);
                $nouveauNomFichier .="_".uniqid();
                $nouveauNomFichier .= "." .$fichier->guessExtension();
                $fichier->move($this->getParameter("dossier_img_plats"),$nouveauNomFichier);
                $plat->setImage($nouveauNomFichier);
            }
            $plat->setLieuVente($this->getUser()->getLieuVente());
            $entityManager->persist($plat);
            $entityManager->flush();
            $this->addFlash("success","plat ajouté avec succès :)");
            return $this->redirectToRoute('app_nhema_admin_plat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/plat/new.html.twig', [
            'plat' => $plat,
            'form' => $form,
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_nhema_admin_plat_show', methods: ['GET'])]
    public function show(Plat $plat, RecetteRepository $recetteRep, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        return $this->render('nhema/admin/plat/show.html.twig', [
            'plat' => $plat,
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
            'recettes' => $recetteRep->findBy(['product' => $plat]),

        ]);
    }

    #[Route('/edit/{id}', name: 'app_nhema_admin_plat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, plat $plat, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        $form = $this->createForm(PlatType::class, $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image =$form->get("image")->getData();
            if ($image) {
                if ($plat->getImage()) {
                    $ancienImage=$this->getParameter("dossier_img_plats")."/".$plat->getImage();
                    if (file_exists($ancienImage)) {
                        unlink($ancienImage);
                    }
                }
                $nomImage= pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $slugger = new AsciiSlugger();
                $nouveauNomImage = $slugger->slug($nomImage);
                $nouveauNomImage .="_".uniqid();
                $nouveauNomImage .= "." .$image->guessExtension();
                $image->move($this->getParameter("dossier_img_plats"),$nouveauNomImage);
                $plat->setImage($nouveauNomImage);

            }
            $entityManager->flush();
            $this->addFlash("success","plat amodifié avec succès :)");
            return $this->redirectToRoute('app_nhema_admin_plat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/plat/edit.html.twig', [
            'plat' => $plat,
            'form' => $form,
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_nhema_admin_plat_delete', methods: ['POST'])]
    public function delete(Request $request, plat $plat, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        if ($this->isCsrfTokenValid('delete'.$plat->getId(), $request->request->get('_token'))) {
            $entityManager->remove($plat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_nhema_admin_plat_index', [], Response::HTTP_SEE_OTHER);
    }
}
