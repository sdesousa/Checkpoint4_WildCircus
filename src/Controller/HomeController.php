<?php

namespace App\Controller;

use App\Repository\ActRepository;
use App\Repository\PriceRepository;
use App\Repository\SpectacleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(SpectacleRepository $spectacleRepository, ActRepository $actRepository, PriceRepository $priceRepository): Response
    {
        $nextSpectacle = $spectacleRepository->findNextSpectacle();
        $lastAct = $actRepository->findOneBy([], ['created' => 'DESC']);
        $price = $priceRepository->findOneBy([]);

        return $this->render('home/index.html.twig', [
            'nextSpectacle' => $nextSpectacle,
            'lastAct' => $lastAct,
            'price' => $price,
        ]);
    }
}
