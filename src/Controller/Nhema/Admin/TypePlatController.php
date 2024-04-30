<?php

namespace App\Controller\Nhema\Admin;

use App\Entity\TypePlat;
use App\Form\TypePlatType;
use App\Repository\TypePlatRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/nhema/admin/type/plat')]
class TypePlatController extends AbstractController
{
    #[Route('/', name: 'app_nhema_admin_type_plat_index', methods: ['GET'])]
    public function index(TypePlatRepository $typePlatRepository): Response
    {
        return $this->render('nhema/admin/type_plat/index.html.twig', [
            'type_plats' => $typePlatRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_nhema_admin_type_plat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntrepriseRepository $entrepriseRep, EntityManagerInterface $entityManager): Response
    {
        $typePlat = new TypePlat();
        $form = $this->createForm(TypePlatType::class, $typePlat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typePlat);
            $entityManager->flush();
            $this->addFlash("success", "Type ajouté avec succès :)");
            return $this->redirectToRoute('app_nhema_admin_plat_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/type_plat/new.html.twig', [
            'categorie_depense' => $typePlat,
            'form' => $form,
            'entreprise' => $entrepriseRep->find(1),
        ]);
    }

    #[Route('/{id}', name: 'app_nhema_admin_type_plat_show', methods: ['GET'])]
    public function show(TypePlat $typePlat): Response
    {
        return $this->render('nhema/admin/type_plat/show.html.twig', [
            'type_plat' => $typePlat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_nhema_admin_type_plat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypePlat $typePlat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypePlatType::class, $typePlat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_nhema_admin_type_plat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/type_plat/edit.html.twig', [
            'type_plat' => $typePlat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_nhema_admin_type_plat_delete', methods: ['POST'])]
    public function delete(Request $request, TypePlat $typePlat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typePlat->getId(), $request->request->get('_token'))) {
            $entityManager->remove($typePlat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_nhema_admin_type_plat_index', [], Response::HTTP_SEE_OTHER);
    }
}
