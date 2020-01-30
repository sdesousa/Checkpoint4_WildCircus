<?php

namespace App\Controller;

use App\Entity\Spectacle;
use App\Repository\SpectacleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/spectacle")
 */
class SpectacleController extends AbstractController
{
    /**
     * @Route("/", name="spectacle_index", methods={"GET"})
     */
    public function index(SpectacleRepository $spectacleRepository): Response
    {
        return $this->render('spectacle/index.html.twig', [
            'spectacles' => $spectacleRepository->findBy([], ['date' => 'DESC']),
        ]);
    }

    /**
     * @Route("/{id}", name="spectacle_show", methods={"GET"})
     */
    public function show(Spectacle $spectacle): Response
    {
        return $this->render('spectacle/show.html.twig', [
            'spectacle' => $spectacle,
        ]);
    }
}
