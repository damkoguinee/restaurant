<?php

namespace App\Controller\Nhema\Admin;

use App\Entity\Chicha;
use App\Form\ChichaType;
use App\Repository\ChichaRepository;
use App\Repository\RecetteRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LieuxVentesRepository;
use App\Repository\ChichaRecetteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/nhema/admin/chicha')]
class ChichaController extends AbstractController
{
    #[Route('/', name: 'app_nhema_admin_chicha_index', methods: ['GET'])]
    public function index(Request $request, ChichaRepository $chichaRepository, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        if ($request->get("search")){
            $search = $request->get("search");
        }else{
            $search = "";
        }
        $pageEncours = $request->get('pageEncours', 1);
        $listechichas = $chichaRepository->listeDeschichasPagination($search, $pageEncours, 15); 
        return $this->render('nhema/admin/chicha/index.html.twig', [
            'chichas' => $listechichas,
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
            'search' => $search
        ]);
    }

    #[Route('/new', name: 'app_nhema_admin_chicha_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        $chicha = new chicha();
        $form = $this->createForm(chichaType::class, $chicha);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fichier = $form->get("image")->getData();
            if ($fichier) {
                $nomFichier= pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                $slugger = new AsciiSlugger();
                $nouveauNomFichier = $slugger->slug($nomFichier);
                $nouveauNomFichier .="_".uniqid();
                $nouveauNomFichier .= "." .$fichier->guessExtension();
                $fichier->move($this->getParameter("dossier_img_chichas"),$nouveauNomFichier);
                $chicha->setImage($nouveauNomFichier);
            }
            $chicha->setLieuVente($this->getUser()->getLieuVente());
            $entityManager->persist($chicha);
            $entityManager->flush();
            $this->addFlash("success","chicha ajouté avec succès :)");
            return $this->redirectToRoute('app_nhema_admin_chicha_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/chicha/new.html.twig', [
            'chicha' => $chicha,
            'form' => $form,
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_nhema_admin_chicha_show', methods: ['GET'])]
    public function show(chicha $chicha, RecetteRepository $recetteRep, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        return $this->render('nhema/admin/chicha/show.html.twig', [
            'chicha' => $chicha,
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
            'recettes' => $recetteRep->findBy(['product' => $chicha]),

        ]);
    }

    #[Route('/edit/{id}', name: 'app_nhema_admin_chicha_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chicha $chicha, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        $form = $this->createForm(chichaType::class, $chicha);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image =$form->get("image")->getData();
            if ($image) {
                if ($chicha->getImage()) {
                    $ancienImage=$this->getParameter("dossier_img_chichas")."/".$chicha->getImage();
                    if (file_exists($ancienImage)) {
                        unlink($ancienImage);
                    }
                }
                $nomImage= pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $slugger = new AsciiSlugger();
                $nouveauNomImage = $slugger->slug($nomImage);
                $nouveauNomImage .="_".uniqid();
                $nouveauNomImage .= "." .$image->guessExtension();
                $image->move($this->getParameter("dossier_img_chichas"),$nouveauNomImage);
                $chicha->setImage($nouveauNomImage);

            }
            $entityManager->flush();
            $this->addFlash("success","chicha amodifié avec succès :)");
            return $this->redirectToRoute('app_nhema_admin_chicha_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/chicha/edit.html.twig', [
            'chicha' => $chicha,
            'form' => $form,
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_nhema_admin_chicha_delete', methods: ['POST'])]
    public function delete(Request $request, Chicha $chicha, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chicha->getId(), $request->request->get('_token'))) {
            $entityManager->remove($chicha);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_nhema_admin_chicha_index', [], Response::HTTP_SEE_OTHER);
    }
}
