<?php

namespace App\Controller\Nhema\Personnel;

use App\Entity\LieuxVentes;
use App\Entity\PrimePersonnel;
use App\Form\PrimePersonnelType;
use App\Repository\UserRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PrimePersonnelRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/nhema/personel/prime/personnels')]
class PrimePersonnelController extends AbstractController
{
    #[Route('/accueil/{lieu_vente}', name: 'app_nhema_personnel_prime_personnel_index', methods: ['GET'])]
    public function index(PrimePersonnelRepository $primePersonnelRepository , LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep): Response
    {
        return $this->render('nhema/personnel/prime_personnel/index.html.twig', [
            'prime_personnels' => $primePersonnelRepository->findBy(['lieuVente' => $lieu_vente], ['id' => 'DESC']),
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,

        ]);
    }

    #[Route('/new/{lieu_vente}', name: 'app_nhema_personnel_prime_personnel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRep, EntityManagerInterface $entityManager , LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep): Response
    {
        $primePersonnel = new PrimePersonnel();
        $form = $this->createForm(PrimePersonnelType::class, $primePersonnel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($primePersonnel);
            $entityManager->flush();

            return $this->redirectToRoute('app_nhema_personnel_prime_personnel_index', ['lieu_vente' => $lieu_vente->getId()], Response::HTTP_SEE_OTHER);
        }
        if ($request->query->get("montant_prime")) {
            $primePersonnel->setMontant($request->query->get("montant_prime"))
                        ->setPeriode(new \DateTime($request->query->get("jour")))
                        ->setPersonnel($userRep->find($request->query->get("personnel")))
                        ->setDateSaisie(new \DateTime("now"))
                        ->setCommentaires($request->query->get("commentaire"))
                        ->setSaisiePar($this->getUser())
                        ->setLieuVente($lieu_vente);
            $entityManager->persist($primePersonnel);
            $entityManager->flush();
            $this->addFlash("success", "Opération ajoutée avec succès :)");
            return $this->redirectToRoute('app_nhema_personnel_prime_personnel_index', ['lieu_vente' => $lieu_vente->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/personnel/prime_personnel/new.html.twig', [
            'prime_personnel' => $primePersonnel,
            'form' => $form,
            'personnels' => $userRep->findBy(['lieuVente' => $lieu_vente, 'typeUser' => 'personnel'], ['prenom' => 'ASC']),
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,

        ]);
    }

    #[Route('/show/{id}/{lieu_vente}', name: 'app_nhema_personnel_prime_personnel_show', methods: ['GET'])]
    public function show(PrimePersonnel $primePersonnel , LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep): Response
    {
        return $this->render('nhema/personnel/prime_personnel/show.html.twig', [
            'prime_personnel' => $primePersonnel,
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,

        ]);
    }

    #[Route('/{id}/edit', name: 'app_nhema_personnel_prime_personnel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PrimePersonnel $primePersonnel, EntityManagerInterface $entityManager , LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep): Response
    {
        $form = $this->createForm(PrimePersonnelType::class, $primePersonnel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_nhema_personnel_prime_personnel_index', ['lieu_vente' => $lieu_vente->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/personnel/prime_personnel/edit.html.twig', [
            'prime_personnel' => $primePersonnel,
            'form' => $form,
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,

        ]);
    }

    #[Route('/delete/{id}/{lieu_vente}', name: 'app_nhema_personnel_prime_personnel_delete', methods: ['POST', 'GET'])]
    public function delete(Request $request, PrimePersonnel $primePersonnel, EntityManagerInterface $entityManager , LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep): Response
    {
        // if ($this->isCsrfTokenValid('delete'.$primePersonnel->getId(), $request->request->get('_token'))) {
        //     $entityManager->remove($primePersonnel);
        //     $entityManager->flush();
        // }
        $entityManager->remove($primePersonnel);
        $entityManager->flush();

        return $this->redirectToRoute('app_nhema_personnel_prime_personnel_index', ['lieu_vente' => $lieu_vente->getId()], Response::HTTP_SEE_OTHER);
    }
}
