<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserController extends ApiController
{
    #[OA\Get(
        path: '/users',
        description: 'List of users',
        summary: 'List of users',
        tags: ['Users'],
        parameters: [

            new OA\Parameter(
                name: 'email',
                description: 'Email of user or part of it',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'string',
                    format: 'email',
                ),
            ),

            new OA\Parameter(
                name: 'page',
                description: 'Page number',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'integer',
                    minimum: 1,
                ),
            ),

            new OA\Parameter(
                name: 'per_page',
                description: 'Count of users per page (0 - returns all filtered items)',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'integer',
                    default: 20,
                    minimum: 0,
                ),
            ),

            new OA\Parameter(
                name: 'query',
                description: 'Query string for filters',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'string',
                ),
            ),

            new OA\Parameter(
                name: 'sort_name',
                description: 'Field name for sorting',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'string',
                    default: 'name',
                    enum: ['name', 'email', 'created_at', 'updated_at'],
                ),
            ),

            new OA\Parameter(
                name: 'sort_dir',
                description: 'Direction of sorting of sort field (asc,desc)',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'string',
                    default: 'asc',
                    enum: ['asc', 'desc'],
                ),
            ),

        ],
        responses: [
            new OA\Response(response: ResponseAlias::HTTP_OK, description: 'List of users by filters'),
        ]
    )]
    public function index(): JsonResponse
    {
        return new JsonResponse(['test' => 'Ok']);
    }

}
