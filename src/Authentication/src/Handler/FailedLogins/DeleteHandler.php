<?php

declare(strict_types=1);

namespace Authentication\Handler\FailedLogins;

use Authentication\Model\FailedLoginModelInterface;
use Authentication\Filter\FailedLogins\DeleteFilter;
use Olobase\Mezzio\Error\ErrorWrapperInterface as Error;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DeleteHandler implements RequestHandlerInterface
{
    public function __construct(
        private FailedLoginModelInterface $failedLoginModel,        
        private DeleteFilter $filter,
        private Error $error,
    ) 
    {
    }
    
    /**
     * @OA\Delete(
     *   path="/failedlogins/delete/{loginId}",
     *   tags={"Failed Logins"},
     *   summary="Delete failed login",
     *   operationId="failedlogins_delete",
     *
     *   @OA\Parameter(
     *       in="path",
     *       name="loginId",
     *       required=true,
     *       description="Failed login uuid",
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
            $this->failedLoginModel->delete(
                $this->filter->getValue('id')
            );
        } else {
            return new JsonResponse($this->error->getMessages($this->filter), 400);
        }
        return new JsonResponse([]);
    }
}
