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
    public function index(SpectacleRepository $spectacleRepository, PriceRepository $priceRepository): Response
    {
        $price = $priceRepository->findOneBy([]);
        $nextSpectacles = $spectacleRepository->findNextSpectacles();
        $previousSpectacles = $spectacleRepository->findPreviousSpectacles();
        return $this->render('spectacle/index.html.twig', [
            'nextSpectacles' => $nextSpectacles,
            'previousSpectacles' => $previousSpectacles,
            'price' => $price,
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
            $totalPrice = $placesKid*$price->getKid()+$placesAdult*$price->getAdult()+$placesSenior*$price->getSenior();
            if ($seats <= $availableSeat) {
                $booking->setUser($this->getUser());
                $booking->setSpectacle($spectacle);
                $booking->setNumberTicket($seats);
                $booking->setTotalPrice($totalPrice);
                $entityManager->persist($booking);
                $entityManager->flush();
                $this->addFlash('success', 'Votre réservation a été enregistrée. Vous avez réservé '.$seats.' places pour '.$totalPrice.' euros.');
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
