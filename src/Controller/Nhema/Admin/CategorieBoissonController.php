<?php

namespace App\Controller\Nhema\Admin;

use App\Entity\CategorieBoisson;
use App\Form\CategorieBoissonType;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategorieBoissonRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/nhema/admin/categorie/boisson')]
class CategorieBoissonController extends AbstractController
{
    #[Route('/', name: 'app_nhema_admin_categorie_boisson_index', methods: ['GET'])]
    public function index(CategorieBoissonRepository $categorieBoissonRepository, EntrepriseRepository $entrepriseRep): Response
    {
        return $this->render('nhema/admin/categorie_boisson/index.html.twig', [
            'categorie_boissons' => $categorieBoissonRepository->findAll(),
            'entreprise' => $entrepriseRep->find(1),
        ]);
    }

    #[Route('/new', name: 'app_nhema_admin_categorie_boisson_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntrepriseRepository $entrepriseRep, EntityManagerInterface $entityManager): Response
    {
        $categorieBoisson = new CategorieBoisson();
        $form = $this->createForm(CategorieBoissonType::class, $categorieBoisson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fichier = $form->get("image")->getData();
            if ($fichier) {
                $nomFichier= pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                $slugger = new AsciiSlugger();
                $nouveauNomFichier = $slugger->slug($nomFichier);
                $nouveauNomFichier .="_".uniqid();
                $nouveauNomFichier .= "." .$fichier->guessExtension();
                $fichier->move($this->getParameter("dossier_img_boissons"),$nouveauNomFichier);
                $categorieBoisson->setImage($nouveauNomFichier);
            }
            $entityManager->persist($categorieBoisson);
            $entityManager->flush();
            $this->addFlash("success", "Catégorie ajoutée avec succès :)");
            return $this->redirectToRoute('app_nhema_admin_boisson_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/categorie_boisson/new.html.twig', [
            'categorie_depense' => $categorieBoisson,
            'form' => $form,
            'entreprise' => $entrepriseRep->find(1),
        ]);
    }

    #[Route('/show/{id}', name: 'app_nhema_admin_categorie_boisson_show', methods: ['GET'])]
    public function show(CategorieBoisson $categorieBoisson, EntrepriseRepository $entrepriseRep): Response
    {
        return $this->render('nhema/admin/categorie_boisson/show.html.twig', [
            'categorie_boisson' => $categorieBoisson,
            'entreprise' => $entrepriseRep->find(1),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_nhema_admin_categorie_boisson_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategorieBoisson $categorieBoisson, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRep): Response
    {
        $form = $this->createForm(CategorieBoissonType::class, $categorieBoisson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image =$form->get("image")->getData();
            if ($image) {
                if ($categorieBoisson->getImage()) {
                    $ancienImage=$this->getParameter("dossier_img_boissons")."/".$categorieBoisson->getImage();
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
                $categorieBoisson->setImage($nouveauNomImage);

            }
            $entityManager->flush();
            $this->addFlash("success","opération amodifiée avec succès :)");
            return $this->redirectToRoute('app_nhema_admin_categorie_boisson_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/categorie_boisson/edit.html.twig', [
            'categorie_boisson' => $categorieBoisson,
            'form' => $form,
            'entreprise' => $entrepriseRep->find(1),
        ]);
    }

    #[Route('/{id}', name: 'app_nhema_admin_categorie_boisson_delete', methods: ['POST'])]
    public function delete(Request $request, CategorieBoisson $categorieBoisson, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorieBoisson->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categorieBoisson);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_nhema_admin_categorie_boisson_index', [], Response::HTTP_SEE_OTHER);
    }
}
