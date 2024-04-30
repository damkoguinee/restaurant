<?php

namespace App\Controller\Nhema\Personnel;

use App\Entity\LieuxVentes;
use App\Entity\AbsencePersonnel;
use App\Repository\UserRepository;
use App\Form\AbsencePersonnelType;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AbsencePersonnelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/nhema/personel/absence/personnel')]
class AbsencePersonnelController extends AbstractController
{
    #[Route('/accueil/{lieu_vente}', name: 'app_nhema_personnel_absence_personnel_index', methods: ['GET'])]
    public function index(AbsencePersonnelRepository $absencePersonnelRepository, LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep): Response
    {
        
        return $this->render('nhema/personnel/absence_personnel/index.html.twig', [
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
            'absences_personnels' => $absencePersonnelRepository->findBy(['lieuVente' => $lieu_vente], ['id' => 'DESC']),
        ]);
    }

    #[Route('/new/{lieu_vente}', name: 'app_nhema_personnel_absence_personnel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LieuxVentes $lieu_vente, UserRepository $userRep, EntityManagerInterface $entityManager,  EntrepriseRepository $entrepriseRep): Response
    {
        $absencePersonnel = new AbsencePersonnel();
        $form = $this->createForm(AbsencePersonnelType::class, $absencePersonnel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($absencePersonnel);
            $entityManager->flush();

            return $this->redirectToRoute('app_nhema_personnel_absence_personnel_index', ['lieu_vente' => $lieu_vente->getId()], Response::HTTP_SEE_OTHER);
        }
        if ($request->query->get("absence")) {
            $absencePersonnel->setHeureAbsence($request->query->get("absence"))    
                        ->setDateAbsence(new \DateTime($request->query->get("jour")))
                        ->setPersonnel($userRep->find($request->query->get("personnel")))
                        ->setDateSaisie(new \DateTime("now"))
                        ->setSaisiePar($this->getUser())
                        ->setLieuVente($lieu_vente);

            $entityManager->persist($absencePersonnel);
            $entityManager->flush();
            $this->addFlash("success", "Opération ajoutée avec succès :)");
            return $this->redirectToRoute('app_nhema_personnel_absence_personnel_index', ['lieu_vente' => $lieu_vente->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/personnel/absence_personnel/new.html.twig', [
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
            'absence_personnel' => $absencePersonnel,
            'form' => $form,
            'personnels' => $userRep->findBy(['lieuVente' => $lieu_vente, 'typeUser' => 'personnel'], ['prenom' => 'ASC']),

        ]);
    }

    #[Route('/show/{id}/{lieu_vente}', name: 'app_nhema_personnel_absence_personnel_show', methods: ['GET'])]
    public function show(AbsencePersonnel $absencePersonnel, LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep): Response
    {
        return $this->render('nhema/personnel/absence_personnel/show.html.twig', [
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
            'absence_personnel' => $absencePersonnel,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_comptable_absence_personnel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AbsencePersonnel $absencePersonnel, EntityManagerInterface $entityManager, LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep): Response
    {
        $form = $this->createForm(AbsencePersonnelType::class, $absencePersonnel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_nhema_personnel_absence_personnel_index', ['lieu_vente' => $lieu_vente->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/personel/absence_personnel/edit.html.twig', [
            'absence_personnel' => $absencePersonnel,
            'form' => $form,

        ]);
    }

    #[Route('/delete/{id}/{lieu_vente}', name: 'app_nhema_personnel_absence_personnel_delete', methods: ['POST', 'GET'])]
    public function delete(Request $request, AbsencePersonnel $absencePersonnel, EntityManagerInterface $entityManager, LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep): Response
    {
        
        $entityManager->remove($absencePersonnel);
        $entityManager->flush();
        

        return $this->redirectToRoute('app_nhema_personnel_absence_personnel_index', ['lieu_vente' => $lieu_vente->getId()], Response::HTTP_SEE_OTHER);
    }
}
