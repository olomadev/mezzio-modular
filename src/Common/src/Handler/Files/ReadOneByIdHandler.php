<?php

declare(strict_types=1);

namespace Common\Handler\Files;

use Common\Model\FileModelInterface;
use Common\Filter\Files\ReadFileFilter;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response;
use Olobase\Mezzio\Error\ErrorWrapperInterface as Error;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\I18n\Translator\TranslatorInterface;

class ReadOneByIdHandler implements RequestHandlerInterface
{
    public function __construct(
        private TranslatorInterface $translator,
        private FileModelInterface $fileModel,
        private ReadFileFilter $filter,
        private Error $error
    )
    {
    }

    /**
     * @OA\Get(
     *   path="/common/files/readOneById/{fileId}",
     *   tags={"Common"},
     *   summary="Find a file by ID and return its content",
     *   operationId="commonFiles_readOne",
     *
     *   @OA\Parameter(
     *       in="path",
     *       name="fileId",
     *       required=true,
     *       description="File id",
     *       @OA\Schema(
     *           type="string",
     *       ),
     *   ),
     *   @OA\Parameter(
     *       in="query",
     *       name="tableName",
     *       required=true,
     *       description="File tableName",
     *       @OA\Schema(
     *           type="string",
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation (File content returned as raw data)",
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="File not found"
     *   )
     *)
     **/
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $get['fileId'] = $queryParams['id'];

        $this->filter->setInputData($get);
        if ($this->filter->isValid()) {
            $row = $this->fileModel->findOneById($get['fileId']);
            
            if (empty($row)) {
                return new JsonResponse([
                    'error' => $this->translator->translate('No document found')
                ], 404);
            }
            $response = new Response('php://temp', 200);
            $response->getBody()->write($row['data']);
            $contentType = $row['type'] ?: 'application/octet-stream'; // Fallback type
            $response = $response->withHeader('Content-Type', $contentType);
            $response = $response->withHeader('Content-Disposition', 'inline; filename="' . basename($row['name']) . '"');
            return $response;
        } else {
            return new JsonResponse($this->error->getMessages($this->filter), 400);
        }
    }
}
