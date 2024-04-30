<?php

namespace App\Controller\Nhema\Sorties;

use App\Entity\Depense;
use App\Form\DepenseType;
use App\Entity\LieuxVentes;
use App\Entity\MouvementCaisse;
use App\Entity\ModifDecaissement;
use App\Entity\DeleteDecaissement;
use App\Entity\Modification;
use App\Repository\DeviseRepository;
use App\Repository\DepenseRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ComptesDepotRepository;
use Symfony\Component\Filesystem\Filesystem;
use App\Repository\CompteOperationRepository;
use App\Repository\MouvementCaisseRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ServicesGenerauxRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategorieOperationRepository;
use App\Repository\ModifDecaissementRepository;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/nhema/sorties/depense')]
class DepenseController extends AbstractController
{
    #[Route('/accueil/{lieu_vente}', name: 'app_nhema_sorties_depense_index', methods: ['GET'])]
    public function index(Request $request, DepenseRepository $depenseRepository, LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep): Response
    {
        if ($request->get("id_client_search")){
            $search = $request->get("id_client_search");
        }else{
            $search = "";
        }

        if ($request->get("date1")){
            $date1 = $request->get("date1");
            $date2 = $request->get("date2");

        }else{
            $date1 = date("Y-01-01");
            $date2 = date("Y-m-d");
        }

        $pageEncours = $request->get('pageEncours', 1);
        if ($request->get("id_client_search")){
            $depense = $depenseRepository->findDepenseByLieuBySearchPaginated($lieu_vente, $search, $date1, $date2, $pageEncours, 25);
        }else{
            $depense = $depenseRepository->findDepenseByLieuPaginated($lieu_vente, $date1, $date2, $pageEncours, 25);
        }
        return $this->render('nhema/sorties/depense/index.html.twig', [
            'depense' => $depense,
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,

        ]);
    }

    #[Route('/new/{lieu_vente}', name: 'app_nhema_sorties_depense_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DeviseRepository $deviseRep, CategorieOperationRepository $catOpRep, MouvementCaisseRepository $mouvCaisseRep, CompteOperationRepository $compteOpRep, EntityManagerInterface $entityManager, LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep): Response
    {
        $depense = new Depense();
        $form = $this->createForm(DepenseType::class, $depense, ['lieu_vente' => $lieu_vente] );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $montantString = $form->get('montant')->getData();
            $montantString = preg_replace('/[^-0-9,.]/', '', $montantString);
            $montant = floatval($montantString);

            $montantString = $form->get('tva')->getData();
            $montantString = preg_replace('/[^-0-9,.]/', '', $montantString);
            $montant_tva = floatval($montantString);

            $caisse = $form->getViewData()->getCaisseRetrait();
            $devise = $form->getViewData()->getDevise();
            $solde_caisse = $mouvCaisseRep->findSoldeCaisse($caisse, $devise);
            if ($solde_caisse >= $montant) {
                $depense->setTraitePar($this->getUser())
                        ->setMontant($montant)
                        ->setTva($montant_tva)
                        ->setLieuVente($lieu_vente)
                        ->setDateSaisie(new \DateTime("now"));
                $fichier = $form->get("justificatif")->getData();
                if ($fichier) {
                    $nomFichier= pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                    $slugger = new AsciiSlugger();
                    $nouveauNomFichier = $slugger->slug($nomFichier);
                    $nouveauNomFichier .="_".uniqid();
                    $nouveauNomFichier .= "." .$fichier->guessExtension();
                    $fichier->move($this->getParameter("dossier_depenses"),$nouveauNomFichier);
                    $depense->setJustificatif($nouveauNomFichier);
                }
                $mouvement = new MouvementCaisse();
                $mouvement->setCategorieOperation($catOpRep->find(2))
                        ->setCompteOperation($compteOpRep->find(3))
                        ->setLieuVente($lieu_vente)
                        ->setSaisiePar($this->getUser())
                        ->setModePaie($form->getViewData()->getModePaiement())
                        ->setTypeMouvement("depense")
                        ->setDateSaisie(new \DateTime("now"))
                        ->setDateOperation($form->getViewData()->getDateDepense())
                        ->setMontant((-1) * $montant)
                        ->setCaisse($form->getViewData()->getCaisseRetrait())
                        ->setDevise($form->getViewData()->getDevise());
                $depense->addMouvementCaiss($mouvement);

                $entityManager->persist($depense);
                $entityManager->flush();
                $this->addFlash("success", "dépense ajoutée avec succès :)");
                return $this->redirectToRoute('app_nhema_sorties_depense_index', ['lieu_vente' => $lieu_vente->getId()], Response::HTTP_SEE_OTHER);
            }else{
                $this->addFlash("warning", "Le montant disponible en caisse est insuffisant");
                // Récupérer l'URL de la page précédente
                $referer = $request->headers->get('referer');
                if ($referer) {
                    $formView = $form->createView();
                    return $this->render('nhema/sorties/depense/new.html.twig', [
                        'entreprise' => $entrepriseRep->find(1),
                        'lieu_vente' => $lieu_vente,
                        'form' => $formView,
                        'depense' => $depense,
                        'referer' => $referer,
                    ]);
                }
            }
            
        }

        return $this->render('nhema/sorties/depense/new.html.twig', [
            'depense' => $depense,
            'form' => $form,
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
        ]);
    }

    #[Route('/show/{id}/{lieu_vente}', name: 'app_nhema_sorties_depense_show', methods: ['GET'])]
    public function show(Depense $depense, LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep): Response
    {
        return $this->render('nhema/sorties/depense/show.html.twig', [
            'depense' => $depense,
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,

        ]);
    }

    #[Route('/edit/{id}/{lieu_vente}', name: 'app_nhema_sorties_depense_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Depense $depense, MouvementCaisseRepository $mouvCaisseRep, DepenseRepository $depenseRep, EntityManagerInterface $entityManager, LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep): Response
    {
        $depense_init = $depenseRep->find($depense);
        $depense_modif = new Modification();
        $depense_modif->setMontant($depense_init->getMontant())
                        ->setCaisse($depense_init->getCaisseRetrait())
                        ->setOrigine("depense")
                        ->setDevise($depense_init->getDevise())
                        ->setTraitePar($depense_init->getTraitePar())
                        ->setDateOperation($depense_init->getDateDepense())
                        ->setDepense($depense_init);
        $entityManager->persist($depense_modif);

        $form = $this->createForm(DepenseType::class, $depense, ['lieu_vente' => $lieu_vente] );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $montantString = $form->get('montant')->getData();
            $montantString = preg_replace('/[^-0-9,.]/', '', $montantString);
            $montant = floatval($montantString);

            $montantString = $form->get('tva')->getData();
            $montantString = preg_replace('/[^-0-9,.]/', '', $montantString);
            $montant_tva = floatval($montantString);

            $caisse = $form->getViewData()->getCaisseRetrait();
            $devise = $form->getViewData()->getDevise();
            $solde_caisse = $mouvCaisseRep->findSoldeCaisse($caisse, $devise);
            if ($solde_caisse >= $montant) {
                $depense->setMontant($montant)
                        ->setTva($montant_tva)
                        ->setTraitePar($this->getUser())
                        ->setDateSaisie(new \DateTime("now"));

                $mouvement = $mouvCaisseRep->findOneBy(['depense' => $depense]);
                $mouvement->setDateSaisie(new \DateTime("now"))
                        ->setMontant($montant)
                        ->setSaisiePar($this->getUser())
                        ->setModePaie($form->getViewData()->getModePaiement())
                        ->setDateOperation($form->getViewData()->getDateDepense())
                        ->setCaisse($form->getViewData()->getCaisseRetrait())
                        ->setDevise($form->getViewData()->getDevise());

                $justificatif =$form->get("justificatif")->getData();
                if ($justificatif) {
                    if ($depense->getJustificatif()) {
                        $ancienJustificatif=$this->getParameter("dossier_depenses")."/".$depense->getJustificatif();
                        if (file_exists($ancienJustificatif)) {
                            unlink($ancienJustificatif);
                        }
                    }
                    $nomJustificatif= pathinfo($justificatif->getClientOriginalName(), PATHINFO_FILENAME);
                    $slugger = new AsciiSlugger();
                    $nouveauNomJustificatif = $slugger->slug($nomJustificatif);
                    $nouveauNomJustificatif .="_".uniqid();
                    $nouveauNomJustificatif .= "." .$justificatif->guessExtension();
                    $justificatif->move($this->getParameter("dossier_depenses"),$nouveauNomJustificatif);
                    $depense->setJustificatif($nouveauNomJustificatif);

                }

                $entityManager->persist($depense);
                $entityManager->persist($depense_modif);
                $entityManager->flush();
                $this->addFlash("success", "Dépense enregistrée avec succès :)");
                return $this->redirectToRoute('app_nhema_sorties_depense_index', ['lieu_vente' => $lieu_vente->getId()], Response::HTTP_SEE_OTHER);
            }else{
                $this->addFlash("warning", "Le montant disponible en caisse est insuffisant");
                // Récupérer l'URL de la page précédente
                $referer = $request->headers->get('referer');
                if ($referer) {
                    $formView = $form->createView();
                    return $this->render('nhema/sorties/depense/edit.html.twig', [
                        'entreprise' => $entrepriseRep->find(1),
                        'lieu_vente' => $lieu_vente,
                        'form' => $formView,
                        'depense' => $depense,
                        'referer' => $referer,
                    ]);
                }
            }

        }

        return $this->render('nhema/sorties/depense/edit.html.twig', [
            'depense' => $depense,
            'form' => $form,
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
        ]);
    }

    #[Route('/delete/{id}/{lieu_vente}', name: 'app_nhema_sorties_depense_delete', methods: ['POST'])]
    public function delete(Request $request, Depense $depense, Filesystem $filesystem, EntityManagerInterface $entityManager, LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep): Response
    {
        $justificatif = $depense->getJustificatif();
        if ($this->isCsrfTokenValid('delete'.$depense->getId(), $request->request->get('_token'))) {
            $pdfPath = $this->getParameter("dossier_depense") . '/' . $justificatif;
            // Si le chemin du justificatif existe, supprimez également le fichier
            if ($justificatif && $filesystem->exists($pdfPath)) {
                $filesystem->remove($pdfPath);
            }

            $delete_dec = new DeleteDecaissement();
            $delete_dec->setMontant($depense->getMontant())
                    ->setDateSaisie(new \DateTime("now"))
                    ->setClient("depense")
                    ->setTraitePar($depense->getTraitePar()->getPrenom().' '.$depense->getTraitePar()->getNom())
                    ->setDevise($depense->getDevise()->getNomDevise())
                    ->setCaisse($depense->getCaisseRetrait()->getDesignation())
                    ->setDateDecaissement($depense->getDateDepense())
                    ->setCommentaire($depense->getDescription());

            $entityManager->persist($delete_dec);
            $entityManager->remove($depense);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_nhema_sorties_depense_index', ['lieu_vente' => $lieu_vente->getId()], Response::HTTP_SEE_OTHER);
    }
}
