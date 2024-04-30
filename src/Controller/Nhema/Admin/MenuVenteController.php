<?php

namespace App\Controller\Nhema\Admin;

use App\Entity\MenuVente;
use App\Form\MenuVenteType;
use App\Repository\MenuVenteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/nhema/admin/menu/vente')]
class MenuVenteController extends AbstractController
{
    #[Route('/', name: 'app_nhema_admin_menu_vente_index', methods: ['GET'])]
    public function index(MenuVenteRepository $menuVenteRepository): Response
    {
        return $this->render('nhema/admin/menu_vente/index.html.twig', [
            'menu_ventes' => $menuVenteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_nhema_admin_menu_vente_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $menuVente = new MenuVente();
        $form = $this->createForm(MenuVenteType::class, $menuVente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($menuVente);
            $entityManager->flush();

            return $this->redirectToRoute('app_nhema_admin_menu_vente_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/menu_vente/new.html.twig', [
            'menu_vente' => $menuVente,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_nhema_admin_menu_vente_show', methods: ['GET'])]
    public function show(MenuVente $menuVente): Response
    {
        return $this->render('nhema/admin/menu_vente/show.html.twig', [
            'menu_vente' => $menuVente,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_nhema_admin_menu_vente_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MenuVente $menuVente, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MenuVenteType::class, $menuVente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_nhema_admin_menu_vente_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/menu_vente/edit.html.twig', [
            'menu_vente' => $menuVente,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_nhema_admin_menu_vente_delete', methods: ['POST'])]
    public function delete(Request $request, MenuVente $menuVente, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$menuVente->getId(), $request->request->get('_token'))) {
            $entityManager->remove($menuVente);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_nhema_admin_menu_vente_index', [], Response::HTTP_SEE_OTHER);
    }
}
