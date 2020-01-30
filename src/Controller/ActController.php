<?php

namespace App\Controller;

use App\Repository\ActRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/act")
 */
class ActController extends AbstractController
{
    /**
     * @Route("/", name="act_index", methods={"GET"})
     */
    public function index(ActRepository $actRepository): Response
    {
        return $this->render('act/index.html.twig', [
            'acts' => $actRepository->findBy([], ['created' => 'DESC']),
        ]);
    }
}
