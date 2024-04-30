<?php

namespace App\Controller\Nhema\Vente;

use App\Entity\Table;
use App\Form\TableType;
use App\Entity\LieuxVentes;
use App\Repository\TableRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\SalleRepository;
use App\Repository\TableCommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/nhema/vente/table')]
class TableController extends AbstractController
{
    #[Route('/{lieu_vente}', name: 'app_nhema_vente_table_index', methods: ['GET'])]
    public function index(Request $request, SessionInterface $session, TableRepository $tableRep, SalleRepository $salleRep, TableCommandeRepository $tableCommandeRep, LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep, EntityManagerInterface $em): Response
    {
        $session_type_vente = $session->get('session_type_vente', null);
        if ($request->get('type_vente')) {
            $session_type_vente = $request->get('type_vente');
        }
        $session->set('session_type_vente', $session_type_vente);
        $salle = $request->get('salle', $salleRep->findOneBy(['lieuVente' => $lieu_vente])->getId());

        $tables = $tableRep->findBy(['lieuVente' => $lieu_vente]);
        foreach ($tables as $emplacement) {
            $commande = $tableCommandeRep->findOneBy(['emplacement' => $emplacement, 'lieuVente' => $lieu_vente]);

            if ($commande) {
                // on change l'etat de la table en occupé
                $table = $tableRep->find($emplacement);
                $table->setEtat("occupe");
                $em->persist($table);
                $em->flush();
            }else{}
        }
        $tables = $tableRep->findBy(['lieuVente' => $lieu_vente, 'salle' => $salle]);
        return $this->render('nhema/vente/table/index.html.twig', [
            'tables' => $tables,
            'salles' => $salleRep->findBy(['lieuVente' => $lieu_vente]),
            'choix_salle' => $salle,
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
            'type_vente' => $session_type_vente
        ]);
    }

    #[Route('/liste/{lieu_vente}', name: 'app_nhema_vente_table_liste', methods: ['GET'])]
    public function listeTable(Request $request, TableRepository $tableRep, SalleRepository $salleRep, LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep): Response
    {
        $tables = $tableRep->findBy(['lieuVente' => $lieu_vente, 'etat' => 'libre']);
        return $this->render('nhema/vente/table/liste_table.html.twig', [
            'tables' => $tables,
            'salles' => $salleRep->findBy(['lieuVente' => $lieu_vente]),
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
        ]);
    }

    #[Route('/new/{lieu_vente}', name: 'app_nhema_vente_table_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SalleRepository $salleRep, EntityManagerInterface $entityManager, LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep): Response
    {
        $salle = $request->get('salle', $salleRep->findOneBy(['lieuVente' => $lieu_vente])->getId());
        $table = new Table();
        $form = $this->createForm(TableType::class, $table);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $table->setEtat("libre")
                ->setLieuVente($lieu_vente);
            $entityManager->persist($table);
            $entityManager->flush();
            $this->addFlash("success", "Table crée avec succès :)");
            return $this->redirectToRoute('app_nhema_vente_table_index', ['lieu_vente' => $lieu_vente->getId(), 'salle' => $salle], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/vente/table/new.html.twig', [
            'table' => $table,
            'form' => $form,
            'salles' => $salleRep->findBy(['lieuVente' => $lieu_vente]),
            'choix_salle' => $salle,
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
        ]);
    }

    #[Route('/{id}', name: 'app_nhema_vente_table_show', methods: ['GET'])]
    public function show(Table $table, LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep): Response
    {
        return $this->render('nhema/vente/table/show.html.twig', [
            'table' => $table,
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_nhema_vente_table_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Table $table, EntityManagerInterface $entityManager, LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep): Response
    {
        $form = $this->createForm(TableType::class, $table);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_nhema_vente_table_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/vente/table/edit.html.twig', [
            'table' => $table,
            'form' => $form,
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
        ]);
    }

    #[Route('/delete/{id}/{lieu_vente}', name: 'app_nhema_vente_table_delete', methods: ['POST'])]
    public function delete(Request $request, Table $table, EntityManagerInterface $entityManager, LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep): Response
    {
        if ($this->isCsrfTokenValid('delete'.$table->getId(), $request->request->get('_token'))) {
            $entityManager->remove($table);
            $entityManager->flush();
            $this->addFlash("success", "table supprimée avec succès :)");
        }
        return $this->redirectToRoute('app_nhema_vente_table_liste', ['lieu_vente' => $lieu_vente->getId()], Response::HTTP_SEE_OTHER);
    }
}
