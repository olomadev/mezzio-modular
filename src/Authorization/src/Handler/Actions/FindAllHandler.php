<?php

declare(strict_types=1);

namespace Authorization\Handler\Actions;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FindAllHandler implements RequestHandlerInterface
{
    /**
     * @OA\Get(
     *   path="/authorization/actions/findAll",
     *   tags={"Authorization Actions"},
     *   summary="Find all actions",
     *   operationId="authorizationActions_findAll",
     *   
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *     @OA\JsonContent(ref="#/components/schemas/CommonFindAll"),
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="No result found"
     *   ),
     *   @OA\Response(
     *      response=500,
     *      description="Internal server error"
     *   )
     *)
     **/
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = [
            ['id' => 'create', 'name' => 'Create'],
            ['id' => 'delete', 'name' => 'Delete'],
            ['id' => 'edit', 'name' => 'Edit'],
            ['id' => 'list', 'name' => 'List'],
            ['id' => 'show', 'name' => 'Show'],
        ];
        return new JsonResponse([
            'data' => $data
        ]);
    }
}
