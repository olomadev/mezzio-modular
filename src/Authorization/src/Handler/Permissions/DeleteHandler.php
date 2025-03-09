<?php

declare(strict_types=1);

namespace Authorization\Handler\Permissions;

use Authorization\Filter\Permissions\DeleteFilter;
use Olobase\Mezzio\Authorization\PermissionModelInterface;
use Olobase\Mezzio\Error\ErrorWrapperInterface as Error;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DeleteHandler implements RequestHandlerInterface
{
    public function __construct(
        private PermissionModelInterface $permissionModel,        
        private DeleteFilter $filter,
        private Error $error,
    ) 
    {
    }
    
    /**
     * @OA\Delete(
     *   path="/authorization/permissions/delete/{permId}",
     *   tags={"Authorization Permissions"},
     *   summary="Delete permission",
     *   operationId="authorizationPermissions_delete",
     *
     *   @OA\Parameter(
     *       in="path",
     *       name="permId",
     *       required=true,
     *       description="Permission uuid",
     *       @OA\Schema(
     *           type="string",
     *           format="uuid",
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *   )
     *)
     **/
    public function handle(ServerRequestInterface $request): ResponseInterface
    {   
        $this->filter->setInputData($request->getQueryParams());
        if ($this->filter->isValid()) {
            $this->permissionModel->delete(
                $this->filter->getValue('id')
            );
        } else {
            return new JsonResponse($this->error->getMessages($this->filter), 400);
        }
        return new JsonResponse([]);
    }
}
