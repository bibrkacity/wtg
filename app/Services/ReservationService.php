<?php

namespace App\Services;

use App\Exceptions\OfferException;
use App\Models\Offer;
use App\Models\Reservation;
use Exception;

class ReservationService
{
    /**
     * @throws OfferException
     */
    public function createReservation(Offer $offer, array $data): ?Reservation
    {
        if ($offer->available_units == 0) {
            throw new OfferException();
        }
        try {
            $reservation = Reservation::create($data);
        } catch (Exception $e) {
            throw new OfferException();
        }

        $offer->decrement('available_units');
        $offer->save();
        return $reservation;

    }
}
