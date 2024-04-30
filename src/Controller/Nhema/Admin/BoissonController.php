<?php

namespace App\Controller\Nhema\Admin;

use App\Entity\Boisson;
use App\Entity\LiaisonProduct;
use App\Form\BoissonType;
use App\Form\LiaisonProductType;
use App\Repository\BoissonRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\LiaisonProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LieuxVentesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/nhema/admin/boisson')]
class BoissonController extends AbstractController
{
    #[Route('/', name: 'app_nhema_admin_boisson_index', methods: ['GET'])]
    public function index(Request $request, BoissonRepository $boissonRepository, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        if ($request->get("search")){
            $search = $request->get("search");
        }else{
            $search = "";
        }
        $pageEncours = $request->get('pageEncours', 1);
        $listeBoissons = $boissonRepository->listeDesBoissonsPagination($search, $pageEncours, 15); 

        return $this->render('nhema/admin/boisson/index.html.twig', [
            'boissons' => $listeBoissons,
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
            'search' => $search
        ]);
    }

    #[Route('/new', name: 'app_nhema_admin_boisson_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep): Response
    {
        $boisson = new Boisson(); // Créez une nouvelle instance de Boisson à l'extérieur de la boucle foreach
        $form = $this->createForm(BoissonType::class, $boisson);
        $form->handleRequest($request);
        $liaison = new LiaisonProduct();

        if ($form->isSubmitted() && $form->isValid()) {
            
            $typeBoissons = $request->get('typeBoisson');
            foreach ($typeBoissons as $type) {
                // Créez une nouvelle instance de Boisson pour chaque type de boisson
                if ($type == 'bouteille') {
                    $boissonBouteille = new Boisson();
                    $boissonBouteille->setNom($boisson->getNom())
                        ->setCategorie($boisson->getCategorie())
                        ->setDescription($boisson->getDescription())
                        ->setPrixVente($boisson->getPrixVente())
                        ->setVolume($boisson->getVolume())
                        ->setUnite($boisson->getUnite())
                        ->setService($boisson->getService())
                        ->setLieuVente($boisson->getLieuVente())
                        ->setTypeBoisson($type);

                    $fichier = $form->get("image")->getData();
                    if ($fichier) {
                        $nomFichier = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                        $slugger = new AsciiSlugger();
                        $nouveauNomFichier = $slugger->slug($nomFichier);
                        $nouveauNomFichier .= "_" . uniqid();
                        $nouveauNomFichier .= "." . $fichier->guessExtension();
                        $fichier->move($this->getParameter("dossier_img_boissons"), $nouveauNomFichier);
                        $boissonBouteille->setImage($nouveauNomFichier);
                    }

                    if (sizeof($typeBoissons) > 1) {
                        $boissonBouteille->addLiaisonProduct($liaison);
                    }
                }

                if ($type == 'verre') {
                    $boissonVerre = new Boisson();
                    $boissonVerre->setNom($boisson->getNom())
                        ->setCategorie($boisson->getCategorie())
                        ->setDescription($boisson->getDescription())
                        ->setService($boisson->getService())
                        ->setLieuVente($boisson->getLieuVente())
                        ->setTypeBoisson($type)
                        ->setPrixVente(0)
                        ->setNom($boisson->getNom() . ' verre')
                        ->setVolume(null)
                        ->setUnite(null);

                    // $fichier = $form->get("image")->getData();
                    // if ($fichier) {
                    //     $nomFichier = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                    //     $slugger = new AsciiSlugger();
                    //     $nouveauNomFichier = $slugger->slug($nomFichier);
                    //     $nouveauNomFichier .= "_" . uniqid();
                    //     $nouveauNomFichier .= "." . $fichier->guessExtension();
                    //     $fichier->move($this->getParameter("dossier_img_boissons"), $nouveauNomFichier);
                    //     $boissonVerre->setImage($nouveauNomFichier);
                    // }

                    if (sizeof($typeBoissons) > 1) {
                        $boissonVerre->addLiaisonProducts2($liaison);
                    }
                }
                $entityManager->persist($boissonBouteille);
                if (sizeof($typeBoissons) > 1 and $type == 'verre') {
                    $entityManager->persist($boissonBouteille);
                    $entityManager->persist($boissonVerre);
                    $entityManager->persist($liaison);
                }else{
                    $entityManager->persist($boissonBouteille);
                }
            }
            $entityManager->flush();
            $this->addFlash("success", "Boisson(s) ajoutée(s) avec succès :)");
            return $this->redirectToRoute('app_nhema_admin_boisson_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/boisson/new.html.twig', [
            'boisson' => $boisson,
            'form' => $form->createView(),
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
        ]);
    }


    #[Route('/{id}', name: 'app_nhema_admin_boisson_show', methods: ['GET', 'POST'])]
    public function show(Boisson $boisson, Request $request, LiaisonProductRepository $liaisonProdRep, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep, EntityManagerInterface $em): Response
    {
        $liaisonProduit = new LiaisonProduct();
        $form = $this->createForm(LiaisonProductType::class, $liaisonProduit,[]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $liaisonProduit->setProduct1($boisson);
            $em->persist($liaisonProduit);
            $em->flush();
            $liaisonProduit = new LiaisonProduct();
        }

        $liasion_prod = $liaisonProdRep->findLiaisonByProduct($boisson);
        return $this->render('nhema/admin/boisson/show.html.twig', [
            'boisson' => $boisson,
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
            'liaisons' => $liasion_prod,
            'liaison_produit' => $liaisonProduit,
            'form' => $form,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_nhema_admin_boisson_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Boisson $boisson, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        $form = $this->createForm(BoissonType::class, $boisson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image =$form->get("image")->getData();
            if ($image) {
                if ($boisson->getImage()) {
                    $ancienImage=$this->getParameter("dossier_img_boissons")."/".$boisson->getImage();
                    if (file_exists($ancienImage)) {
                        unlink($ancienImage);
                    }
                }
                $nomImage= pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $slugger = new AsciiSlugger();
                $nouveauNomImage = $slugger->slug($nomImage);
                $nouveauNomImage .="_".uniqid();
                $nouveauNomImage .= "." .$image->guessExtension();
                $image->move($this->getParameter("dossier_img_boissons"),$nouveauNomImage);
                $boisson->setImage($nouveauNomImage);

            }
            $entityManager->flush();
            $this->addFlash("success","Boisson amodifiée avec succès :)");
            return $this->redirectToRoute('app_nhema_admin_boisson_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/boisson/edit.html.twig', [
            'boisson' => $boisson,
            'form' => $form,
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_nhema_admin_boisson_delete', methods: ['POST'])]
    public function delete(Request $request, Boisson $boisson, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        if ($this->isCsrfTokenValid('delete'.$boisson->getId(), $request->request->get('_token'))) {
            $entityManager->remove($boisson);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_nhema_admin_boisson_index', [], Response::HTTP_SEE_OTHER);
    }
}
