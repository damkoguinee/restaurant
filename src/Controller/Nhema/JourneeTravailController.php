<?php

namespace App\Controller\Nhema;

use App\Entity\JourneeTravail;
use App\Entity\LieuxVentes;
use App\Form\JourneeTravailType;
use App\Repository\EntrepriseRepository;
use App\Repository\JourneeTravailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/nhema/journee/travail')]
class JourneeTravailController extends AbstractController
{
    #[Route('/home/{lieu_vente}', name: 'app_nhema_journee_travail_index', methods: ['GET'])]
    public function index(LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep, JourneeTravailRepository $journeeTravailRepository): Response
    {
        
        return $this->render('nhema/journee_travail/index.html.twig', [
            'journee_travails' => $journeeTravailRepository->findBy(['lieuVente' => $lieu_vente], ['id' => 'DESC']),
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
        ]);
    }

    #[Route('/new/{lieu_vente}', name: 'app_nhema_journee_travail_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, LieuxVentes $lieu_vente, SessionInterface $session, EntrepriseRepository $entrepriseRep): Response
    {
        $journeeTravail = new JourneeTravail();
        $form = $this->createForm(JourneeTravailType::class, $journeeTravail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $journeeTravail->setSaisiePar($this->getUser())
                        ->setLieuVente($lieu_vente)
                        ->setDateSaisie(new \DateTime("now"));
            $entityManager->persist($journeeTravail);
            $entityManager->flush();
            $this->addFlash("success", "Une nouvelle journée de travail commence");
            return $this->redirectToRoute('app_nhema_home_lieuvente', ['id' => $lieu_vente->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/journee_travail/new.html.twig', [
            'journee_travail' => $journeeTravail,
            'form' => $form,
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
        ]);
    }

    #[Route('/show/{id}/{lieu_vente}', name: 'app_nhema_journee_travail_show', methods: ['GET'])]
    public function show(JourneeTravail $journeeTravail, LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep): Response
    {
        return $this->render('nhema/journee_travail/show.html.twig', [
            'journee_travail' => $journeeTravail,
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
        ]);
    }

    #[Route('/edit/{id}/{lieu_vente}', name: 'app_nhema_journee_travail_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, JourneeTravail $journeeTravail, EntityManagerInterface $entityManager, LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep): Response
    {
        $form = $this->createForm(JourneeTravailType::class, $journeeTravail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $etat = $form->getViewData()->getstatut();
            if ($etat == 'clos') {
                $journeeTravail->setDateCloture(new \DateTime("now"))
                        ->setCloturerPar($this->getUser());
                $entityManager->flush();
                $this->addFlash("success", "Journée clôturer avec succés :) ");
                return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
            }else{
                $journeeTravail->setSaisiePar($this->getUser())
                        ->setDateSaisie(new \DateTime("now"));

                $entityManager->flush();
                $this->addFlash("success", "Journée modifée avec succés :) ");
                return $this->redirectToRoute('app_nhema_journee_travail_index', [], Response::HTTP_SEE_OTHER);
            }
            
        }

        return $this->render('nhema/journee_travail/edit.html.twig', [
            'journee_travail' => $journeeTravail,
            'form' => $form,
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
        ]);
    }

    #[Route('delete/{id}/{lieu_vente}', name: 'app_nhema_journee_travail_delete', methods: ['POST'])]
    public function delete(Request $request, JourneeTravail $journeeTravail, EntityManagerInterface $entityManager, LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep): Response
    {
        if ($this->isCsrfTokenValid('delete'.$journeeTravail->getId(), $request->request->get('_token'))) {
            $entityManager->remove($journeeTravail);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_nhema_journee_travail_index', [], Response::HTTP_SEE_OTHER);
    }
}
