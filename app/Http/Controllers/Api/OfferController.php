<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\OfferException;
use App\Exceptions\OfferLockedException;
use App\Http\FormRequests\ReservationStoreFormRequest;
use App\Models\Offer;
use App\Services\ReservationService;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class OfferController extends ApiController
{
    public function __construct(private readonly ReservationService $reservationService)
    {
    }

    /**
     * @throws OfferException
     * @throws OfferLockedException
     */
    #[OA\Post(
        path: '/offers/{offer}/reservation',
        description: 'Create a reservation for a specific offer',
        summary: 'Create offer reservation',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: [
                    'client_reference',
                    'customer_name',
                    'customer_email',
                ],
                properties: [
                    new OA\Property(
                        property: 'client_reference',
                        type: 'string',
                        example: 'web-order-9f782b1c'
                    ),
                    new OA\Property(
                        property: 'customer_name',
                        type: 'string',
                        example: 'John Smith'
                    ),
                    new OA\Property(
                        property: 'customer_email',
                        type: 'string',
                        format: 'email',
                        example: 'john@example.com'
                    ),
                ],
                type: 'object',
            ),
        ),
        tags: ['Offers'],
        parameters: [
            new OA\Parameter(
                name: 'offer',
                description: 'Offer ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: ResponseAlias::HTTP_CREATED,
                description: 'Reservation created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'reservation',
                            properties: [
                                new OA\Property(
                                    property: 'client_reference',
                                    type: 'string',
                                    example: 'web-order-9f782b1c'
                                ),
                                new OA\Property(
                                    property: 'customer_name',
                                    type: 'string',
                                    example: 'John Smith'
                                ),
                                new OA\Property(
                                    property: 'customer_email',
                                    type: 'string',
                                    format: 'email',
                                    example: 'john@example.com'
                                ),
                                new OA\Property(
                                    property: 'offer_id',
                                    type: 'integer',
                                    example: 3
                                ),
                                new OA\Property(
                                    property: 'updated_at',
                                    type: 'string',
                                    format: 'date-time',
                                    example: '2026-09-08T14:28:59.000000Z'
                                ),
                                new OA\Property(
                                    property: 'created_at',
                                    type: 'string',
                                    format: 'date-time',
                                    example: '2026-09-08T14:28:59.000000Z'
                                ),
                                new OA\Property(
                                    property: 'id',
                                    type: 'integer',
                                    example: 1
                                ),
                            ],
                            type: 'object'
                        ),
                    ],
                    type: 'object'
                ),
            ),
            new OA\Response(response: ResponseAlias::HTTP_LOCKED, description: 'Offer is locked by another reservation'),
            new OA\Response(response: ResponseAlias::HTTP_NOT_FOUND, description: 'Offer not found'),
        ]
    )]
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
