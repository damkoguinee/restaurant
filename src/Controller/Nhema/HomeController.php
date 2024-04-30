<?php

namespace App\Controller\Nhema;

use App\Entity\Entreprise;
use App\Entity\LieuxVentes;
use App\Repository\EntrepriseRepository;
use App\Repository\JourneeTravailRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LieuxVentesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/nhema/home')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_nhema_home')]
    public function index(EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuxVentesRep): Response
    {
        $user = $this->getUser();
        if ($this->isGranted('ROLE_ADMIN') or $this->isGranted('ROLE_DEVELOPPEUR') || $this->isGranted('ROLE_RESPONSABLE')) { 
            $lieux_ventes = $lieuxVentesRep->findAll();
        } else {
            $lieux_ventes = $lieuxVentesRep->findBy(['id' => $user->getLieuVente()->getId()]);
        }
        return $this->render('nhema/home/index.html.twig', [
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieux_ventes,
        ]);
    }

    #[Route('/lieuvente/{id}', name: 'app_nhema_home_lieuvente', methods: ['GET', 'POST'])]
    public function homeLieuVente(LieuxVentes $lieuxVentes, Request $request, SessionInterface $session, JourneeTravailRepository $journeeRep, EntrepriseRepository $entrepriseRep, EntityManagerInterface $entityManager): Response
    {
        $journee = $journeeRep->findOneBy(['lieuVente' => $lieuxVentes, 'statut' => 'en-cours'], ['jourDeTravail' => 'DESC']);
        // $journee = $journeeRep->findOneBy(['statut' => 'en-cours'], ['jourDeTravail' => 'DESC']);
        $session_jour = $session->get("session_jour", null);
        $session_jour = $journee;
        $session->set('session_jour', $session_jour);
        // if (empty($session_jour)) {
        //     return $this->redirectToRoute('app_nhema_journee_travail_new', ['lieu_vente' => $lieuxVentes->getId()], Response::HTTP_SEE_OTHER);
        // }
        return $this->render('nhema/home/choix.html.twig', [
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieuxVentes,
            'jour' => $session_jour,
        ]);
    }

    #[Route('/typevente/{lieu_vente}', name: 'app_nhema_home_type_vente', methods: ['GET', 'POST'])]
    public function typeVente(LieuxVentes $lieu_vente, Request $request, SessionInterface $session, JourneeTravailRepository $journeeRep, EntrepriseRepository $entrepriseRep, EntityManagerInterface $entityManager): Response
    {
        $session = $request->getSession();
        $session->remove("session_remise_glob");
        $session->remove("montant_paye");
        $session->remove("frais_sup");
        $session->remove("session_client");
        $session->remove("session_type_vente");
        $session->remove("session_table");
        
        return $this->render('nhema/home/type_vente.html.twig', [
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
        ]);
    }
}
