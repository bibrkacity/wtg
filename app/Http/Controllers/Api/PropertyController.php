<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\FormRequests\PropertyIndexFormRequest;
use App\Http\FormResponses\IndexResponse;
use App\Repositories\PropertyRepository;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PropertyController extends Controller
{
    public function __construct(private readonly PropertyRepository $propertyRepository)
    {
    }

    #[OA\Get(
        path: '/properties',
        description: 'Get list of properties with optional filters',
        summary: 'Get properties',
        tags: ['Properties'],
        parameters: [
            new OA\Parameter(
                name: 'city',
                description: 'Filter by city name',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: 'London')
            ),
            new OA\Parameter(
                name: 'check_in',
                description: 'Check-in date',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', format: 'date', example: '2026-10-01')
            ),
            new OA\Parameter(
                name: 'check_out',
                description: 'Check-out date',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', format: 'date', example: '2026-10-07')
            ),
            new OA\Parameter(
                name: 'guests',
                description: 'Number of guests',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 2, minimum: 1)
            ),
            new OA\Parameter(
                name: 'page',
                description: 'Number of page',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 1, default: 1, minimum: 1)
            ),
            new OA\Parameter(
                name: 'per_page',
                description: 'Number of item per page',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 20, default: 20, minimum: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: ResponseAlias::HTTP_OK,
                description: 'List of properties',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'code', type: 'string', example: 'property-001'),
                                    new OA\Property(property: 'name', type: 'string', example: 'Hotel Example'),
                                    new OA\Property(property: 'city', type: 'string', example: 'London'),
                                ],
                                type: 'object',
                            ),
                        ),
                    ],
                    type: 'object',
                ),
            ),
        ]
    )]
    public function index(PropertyIndexFormRequest $request): IndexResponse
    {
        $filters = $request->validated();
        $ApiResponseData = $this->propertyRepository->ApiResponseData($filters);
        return new IndexResponse($ApiResponseData['data'], $filters, $ApiResponseData['total']);
    }
}
