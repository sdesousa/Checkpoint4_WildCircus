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

    /**
     * @Route("/admin/act/{id}/edit", name="admin_act_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Act $act): Response
    {
        $form = $this->createForm(ActType::class, $act);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $act->setUpdatedAt(new DateTime());
            $entityManager->persist($act);
            $entityManager->flush();
            $this->addFlash('success', 'Votre numéro a été modifié');
            return $this->redirectToRoute('admin_act_index');
        }
        return $this->render('act/edit.html.twig', [
            'act' => $act,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/act/{id}", name="admin_act_show", methods={"GET"})
     */
    public function show(Act $act): Response
    {
        return $this->render('act/show.html.twig', [
            'act' => $act,
        ]);
    }

    /**
     * @Route("/admin/act/{id}", name="admin_act_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Act $act): Response
    {
        if ($this->isCsrfTokenValid('delete'.$act->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($act);
            $entityManager->flush();
            $this->addFlash('danger', 'Votre numéro a été supprimé');
        }

        return $this->redirectToRoute('admin_act_index');
    }
}
