<?php

declare(strict_types=1);

namespace Authentication\Handler\FailedLogins;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Authentication\Model\FailedLoginModelInterface;

class FindAllUsernamesHandler implements RequestHandlerInterface
{
    public function __construct(private FailedLoginModelInterface $failedLoginModel)
    {
    }

    /**
     * @OA\Get(
     *   path="/failedloginusernames/findAll",
     *   tags={"Failed Logins"},
     *   summary="Find all usernames for failed logins",
     *   operationId="failedloginusernames_findAll",
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
        $allIpData = $this->failedLoginModel->findAllUsernames();
        return new JsonResponse([
            'data' => $allIpData,
        ]);
    }

}
