<?php

namespace App\Services;

use App\Entity\Spectacle;

class SeatAvailability
{

    public function availableSeats(Spectacle $spectacle)
    {
        $bookings = $spectacle->getBookings();
        $reservedSeats = 0;
        foreach ($bookings as $booking) {
            $reservedSeats += $booking->getNumberTicket();
        }
        return $spectacle->getCapacity()-$reservedSeats;
    }


}
