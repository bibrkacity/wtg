<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\OfferLockedException;
use App\Http\FormRequests\ReservationStoreFormRequest;
use App\Models\Offer;
use App\Services\ReservationService;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Http\JsonResponse;

class OfferController extends ApiController
{
    public function __construct(private readonly ReservationService $reservationService)
    {
    }

    /**
     * @throws OfferLockedException|\App\Exceptions\OfferException
     */
    public function reservation(ReservationStoreFormRequest $request, Offer $offer): JsonResponse
    {
        $data = $request->validated();
        $data['offer_id'] = $offer->id;

        $lock = Cache::lock('offer'.$offer->id, 5);

        if ($lock->get()) {

            $reservation = $this->reservationService->createReservation($offer, $data);

            $lock->release();

            return response()->json(
                ['reservation' => $reservation->toArray()],
                ResponseAlias::HTTP_CREATED
            );

        } else {
            throw new OfferLockedException();
        }

    }

}
