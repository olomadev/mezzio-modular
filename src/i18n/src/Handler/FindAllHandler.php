<?php

declare(strict_types=1);

namespace i18n\Handler;

use i18n\Model\i18nModelInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FindAllHandler implements RequestHandlerInterface
{
    public function __construct(private i18nModelInterface $i18nModel)
    {
    }

    /**
     * @OA\Get(
     *   path="/i18n/languages/findAll",
     *   tags={"i18n Settings"},
     *   summary="Find all i18n settings",
     *   operationId="i18nLanguages_findAll",
     *   
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *     @OA\JsonContent(ref="#/components/schemas/i18nLanguagesFindAll"),
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="No result found"
     *   )
     *)
     **/
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $this->i18nModel->findAll();
        return new JsonResponse([
            'data' => $data,
        ]);
    }

}
