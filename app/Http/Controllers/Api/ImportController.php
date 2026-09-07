<?php

namespace App\Http\Controllers\Api;

use App\Http\FormRequests\ImportStoreFormRequest;
use App\Models\Import;
use App\Exceptions\ImportService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use OpenApi\Attributes as OA;
use Illuminate\Http\JsonResponse;

class ImportController extends ApiController
{
    public function __construct(private readonly ImportService $importService)
    {
    }
    #[OA\Post(
        path: '/imports',
        description: 'Get offers of suppliers',
        summary: 'Get offers of suppliers',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: [
                    'supplier',
                    'external_import_id',
                    'sent_at',
                    'offers',
                ],
                properties: [
                    new OA\Property(property: 'supplier', type: 'string', example: 'supplier-code'),
                    new OA\Property(property: 'external_import_id', type: 'string', example: 'import-12345'),
                    new OA\Property(property: 'sent_at', type: 'string', format: 'date-time', example: '2026-09-04T12:00:00Z'),
                    new OA\Property(
                        property: 'offers',
                        type: 'array',
                        items: new OA\Items(
                            required: [
                                'external_id',
                                'property',
                                'check_in',
                                'check_out',
                                'max_guests',
                                'price',
                                'currency',
                                'available_units',
                            ],
                            properties: [
                                new OA\Property(property: 'external_id', type: 'string', example: 'offer-12345'),
                                new OA\Property(
                                    property: 'property',
                                    required: [
                                        'code',
                                        'name',
                                        'city',
                                    ],
                                    properties: [
                                        new OA\Property(property: 'code', type: 'string', example: 'property-001'),
                                        new OA\Property(property: 'name', type: 'string', example: 'Hotel Example'),
                                        new OA\Property(property: 'city', type: 'string', example: 'London'),
                                    ],
                                    type: 'object',
                                ),
                                new OA\Property(property: 'check_in', type: 'string', format: 'date', example: '2026-10-01'),
                                new OA\Property(property: 'check_out', type: 'string', format: 'date', example: '2026-10-07'),
                                new OA\Property(property: 'max_guests', type: 'integer', example: 2, minimum: 1),
                                new OA\Property(property: 'price', type: 'number', format: 'float', example: 199.99, minimum: 0),
                                new OA\Property(property: 'currency', type: 'string', example: 'USD', maxLength: 3, minLength: 3),
                                new OA\Property(property: 'available_units', type: 'integer', example: 5, minimum: 0),
                                new OA\Property(property: 'expires_at', type: 'string', format: 'date-time', example: '2026-09-30T23:59:59Z', nullable: true),
                            ],
                            type: 'object',
                        ),
                    ),
                ],
                type: 'object',
            ),
        ),
        tags: ['Imports'],
        responses: [
            new OA\Response(
                response: ResponseAlias::HTTP_ACCEPTED,
                description: 'Id and status of the import',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'status', type: 'string', example: 'pending', enum: ['pending', 'processing', 'completed', 'failed']),
                    ],
                    type: 'object',
                ),
            ),
        ]
    )]
    public function store(ImportStoreFormRequest  $request): JsonResponse
    {
        $data = $request->validated();
        $import = $this->importService->import($data);
        return response()->json(
            [
                'id' => $import->id,
                'status' => $import->status
            ],
            ResponseAlias::HTTP_ACCEPTED
        );
    }

    #[OA\Get(
        path: '/imports/{id}',
        description: 'Get details of a specific import',
        summary: 'Get import details',
        tags: ['Imports'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Import ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: ResponseAlias::HTTP_OK,
                description: 'Import details',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'supplier_id', type: 'integer', example: 1),
                                new OA\Property(property: 'external_import_id', type: 'string', example: 'import-12345'),
                                new OA\Property(property: 'sent_at', type: 'string', format: 'date-time', example: '2026-09-04T12:00:00Z'),
                                new OA\Property(property: 'status', type: 'string', example: 'completed', enum: ['pending', 'processing', 'completed', 'failed']),
                                new OA\Property(property: 'total_offers', type: 'integer', example: 10),
                                new OA\Property(property: 'processed_offers', type: 'integer', example: 10),
                                new OA\Property(property: 'error', type: 'string', example: null, nullable: true),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-09-04T12:00:00Z'),
                                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-09-04T12:30:00Z'),
                            ],
                            type: 'object',
                        ),
                    ],
                    type: 'object',
                ),
            ),
            new OA\Response(response: ResponseAlias::HTTP_NOT_FOUND, description: 'Import not found'),
        ]
    )]
    public function show(Import $import): JsonResponse
    {
        return response()->json(['data' => $import->toShowArray()]);
    }

}
