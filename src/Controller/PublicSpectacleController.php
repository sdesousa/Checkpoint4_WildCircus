<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Spectacle;
use App\Form\BookingType;
use App\Repository\SpectacleRepository;
use App\Repository\PriceRepository;
use App\Services\SeatAvailability;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/spectacle")
 */
class PublicSpectacleController extends AbstractController
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
     * @Route("/{id}", name="spectacle_show", methods={"GET","POST"})
     */
    public function show(Request $request, Spectacle $spectacle, PriceRepository $priceRepository, SeatAvailability $seatAvailability): Response
    {
        $price = $priceRepository->findOneBy([]);
        $availableSeat = $seatAvailability->availableSeats($spectacle);
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $placesKid = $request->request->get('booking')['placesKid'];
            $placesAdult = $request->request->get('booking')['placesAdult'];
            $placesSenior = $request->request->get('booking')['placesSenior'];
            $seats = $placesKid + $placesAdult + $placesSenior;
            if ($seats <= $availableSeat) {
                $booking->setSpectacle($spectacle);
                $booking->setNumberTicket($seats);
                $booking->setTotalPrice($placesKid*$price->getKid()+$placesAdult*$price->getAdult()+$placesSenior*$price->getSenior());
                $entityManager->persist($booking);
                $entityManager->flush();
                $this->addFlash('success', 'Votre réservation a été enregistré');
                return $this->redirectToRoute('home');
            } else {
                $this->addFlash('danger', 'Pas assez de places disponible');
                return $this->redirectToRoute('spectacle_show', ['id' => $spectacle->getId()]);
            }
        }

        return $this->render('spectacle/show.html.twig', [
            'spectacle' => $spectacle,
            'availableSeat' => $availableSeat,
            'price' => $price,
            'form' => $form->createView()
        ]);
    }
}
