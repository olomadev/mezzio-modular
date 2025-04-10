<?php

declare(strict_types=1);

namespace Authorization\Handler\Roles;

use Authorization\Model\RoleModelInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FindAllHandler implements RequestHandlerInterface
{
    public function __construct(private RoleModelInterface $roleModel)
    {
    }

    /**
     * @OA\Get(
     *   path="/authorization/roles/findAll",
     *   tags={"Authorization Roles"},
     *   summary="Find all roles",
     *   operationId="authorizationRoles_findAll",
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
        $get = $request->getQueryParams();
        $data = $this->roleModel->findAll($get);
        return new JsonResponse([
            'data' => $data,
        ]);
    }

}
