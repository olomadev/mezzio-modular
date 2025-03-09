<?php

declare(strict_types=1);

namespace Common\Handler\Files;

use Common\Model\FileModelInterface;
use Common\Filter\Files\ReadFileFilter;
use Olobase\Mezzio\Error\ErrorWrapperInterface as Error;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\TextResponse;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\I18n\Translator\TranslatorInterface;

class FindOneByIdHandler implements RequestHandlerInterface
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
     *   path="/common/files/findOneById/{fileId}",
     *   tags={"Common"},
     *   summary="Find a file by ID",
     *   operationId="commonFiles_findOne",
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
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation (File content returned as Base64 string)",
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
            $response = $response->withHeader('Pragma', 'public');
            $response = $response->withHeader('Expires', 0);
            $response = $response->withHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
            $response = $response->withHeader('Content-Type', 'application/octet-stream');
            $response = $response->withHeader('Content-Disposition', 'attachment; filename="'.basename($row['name']).'"');
            $response = $response->withHeader('Content-Transfer-Encoding', 'binary');
            return $response;
        } else {
            return new JsonResponse($this->error->getMessages($this->filter), 400);
        }
    }
}
