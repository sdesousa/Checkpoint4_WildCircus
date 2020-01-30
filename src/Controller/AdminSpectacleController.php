<?php

namespace App\Controller;

use App\Entity\Spectacle;
use App\Form\SpectacleType;
use App\Repository\SpectacleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \DateTime;

/**
 * @Route("/admin/spectacle")
 */
class AdminSpectacleController extends AbstractController
{
    /**
     * @Route("/", name="admin_spectacle_index", methods={"GET"})
     */
    public function index(SpectacleRepository $spectacleRepository): Response
    {
        return $this->render('spectacle/index_admin.html.twig', [
            'spectacles' => $spectacleRepository->findBy([], ['date' => 'DESC']),
        ]);
    }

    /**
     * @Route("/new", name="admin_spectacle_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $spectacle = new Spectacle();
        $form = $this->createForm(SpectacleType::class, $spectacle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $spectacle->setUpdatedAt(new DateTime());
            $entityManager->persist($spectacle);
            $entityManager->flush();
            $this->addFlash('success', 'Votre spectacle a été créé');
            return $this->redirectToRoute('admin_spectacle_index');
        }
        return $this->render('spectacle/new.html.twig', [
            'spectacle' => $spectacle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_spectacle_show", methods={"GET"})
     */
    public function show(Spectacle $spectacle): Response
    {
        return $this->render('spectacle/show_admin.html.twig', [
            'spectacle' => $spectacle,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_spectacle_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Spectacle $spectacle): Response
    {
        $form = $this->createForm(SpectacleType::class, $spectacle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $spectacle->setUpdatedAt(new DateTime());
            $entityManager->persist($spectacle);
            $entityManager->flush();
            $this->addFlash('success', 'Votre spectacle a été modifié');
            return $this->redirectToRoute('admin_spectacle_index');
        }
        return $this->render('spectacle/edit.html.twig', [
            'spectacle' => $spectacle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_spectacle_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Spectacle $spectacle): Response
    {
        if ($this->isCsrfTokenValid('delete'.$spectacle->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($spectacle);
            $entityManager->flush();
            $this->addFlash('danger', 'Votre spectacle a été supprimé');
        }

        return $this->redirectToRoute('admin_spectacle_index');
    }
}
