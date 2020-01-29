<?php

namespace App\Controller;

use App\Entity\Act;
use App\Form\ActType;
use App\Repository\ActRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \DateTime;

class AdminActController extends AbstractController
{
    /**
     * @Route("/admin/act", name="admin_act_index")
     */
    public function index(ActRepository $actRepository)
    {
        return $this->render('act/index_admin.html.twig', [
            'acts' => $actRepository->findBy([], ['created' => 'DESC']),
        ]);
    }

    /**
     * @Route("/admin/act/new", name="admin_act_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $act = new Act();
        $form = $this->createForm(ActType::class, $act);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $act->setUpdatedAt(new DateTime());
            $entityManager->persist($act);
            $entityManager->flush();
            $this->addFlash('success', 'Votre numéro a été créé');
            return $this->redirectToRoute('admin_act_index');
        }
        return $this->render('act/new.html.twig', [
            'act' => $act,
            'form' => $form->createView(),
        ]);
    }
}
