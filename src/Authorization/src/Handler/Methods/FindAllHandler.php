<?php

declare(strict_types=1);

namespace Authorization\Handler\Methods;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FindAllHandler implements RequestHandlerInterface
{
    /**
     * @OA\Get(
     *   path="/authorization/methods/findAll",
     *   tags={"Authorization Methods"},
     *   summary="Find all allowed http methods",
     *   operationId="authorizationMethods_findAll",
     *   
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *     @OA\JsonContent(ref="#/components/schemas/CommonFindAll"),
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="No result found"
     *   )
     *)
     **/
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = [
            ['id' => 'POST', 'name' => 'POST'],
            ['id' => 'GET', 'name' => 'GET'],
            ['id' => 'PUT', 'name' => 'PUT'],
            ['id' => 'DELETE', 'name' => 'DELETE'],
            ['id' => 'PATCH', 'name' => 'PATCH'],
        ];
        return new JsonResponse([
            'data' => $data
        ]);
    }

}
