<?php

namespace App\Controller;

use App\Repository\EntrepriseRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/home')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntrepriseRepository $entrepriseRep): Response
    {
        $entreprise = $entrepriseRep->find(1);
        return $this->render('base.html.twig', [
            'entreprise' => $entreprise,
        ]);
    }
}
