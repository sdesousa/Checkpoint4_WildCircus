<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use App\Repository\PriceRepository;
use App\Services\SeatAvailability;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/booking")
 */
class BookingController extends AbstractController
{
    /**
     * @Route("/", name="admin_booking_index", methods={"GET"})
     */
    public function index(BookingRepository $bookingRepository): Response
    {
        return $this->render('booking/index.html.twig', [
            'bookings' => $bookingRepository->findBy([], ['user' => 'ASC']),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_booking_show", methods={"GET"})
     */
    public function show(Booking $booking): Response
    {
        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_booking_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Booking $booking, PriceRepository $priceRepository, SeatAvailability $seatAvailability): Response
    {
        $price = $priceRepository->findOneBy([]);
        $availableSeat = $seatAvailability->availableSeats($booking->getSpectacle());
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $placesKid = $request->request->get('booking')['placesKid'];
            $placesAdult = $request->request->get('booking')['placesAdult'];
            $placesSenior = $request->request->get('booking')['placesSenior'];
            $seats = $placesKid + $placesAdult + $placesSenior;
            if ($seats <= $availableSeat) {
                $booking->setNumberTicket($seats);
                $booking->setTotalPrice($placesKid*$price->getKid()+$placesAdult*$price->getAdult()+$placesSenior*$price->getSenior());
                $entityManager->persist($booking);
                $entityManager->flush();
                $this->addFlash('success', 'Votre réservation a été modifiée');
                return $this->redirectToRoute('admin_booking_index');
            } else {
                $this->addFlash('danger', 'Pas assez de places disponible');
                return $this->redirectToRoute('admin_booking_edit', ['id' => $booking->getId()]);
            }
        }

        return $this->render('booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_booking_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Booking $booking): Response
    {
        if ($this->isCsrfTokenValid('delete'.$booking->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($booking);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_booking_index');
    }
}
