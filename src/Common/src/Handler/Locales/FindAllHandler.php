<?php

declare(strict_types=1);

namespace Common\Handler\Locales;

use Common\Model\CommonModelInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FindAllHandler implements RequestHandlerInterface
{
    public function __construct(private CommonModelInterface $commonModel)
    {
    }

    /**
     * @OA\Get(
     *   path="/common/locales/findAll",
     *   tags={"Common"},
     *   summary="Find all locales",
     *   operationId="commonLocales_findAll",
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
        $data = $this->commonModel->findLocales();
        return new JsonResponse([
            'data' => $data
        ]);
    }

}
