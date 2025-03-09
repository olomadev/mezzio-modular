<?php

declare(strict_types=1);

namespace Common\Middleware;

use function array_key_first;

use Mezzio\Router\RouteResult;
use Olobase\Mezzio\Exception\BodyDecodeException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\I18n\Translator\TranslatorInterface;

class JsonBodyParserMiddleware implements MiddlewareInterface
{
    public function __construct(private TranslatorInterface $translator)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $headers = $request->getHeaders();
        $routeResult = $request->getAttribute(RouteResult::class);
        $primaryKey = 'id';

        // If RouteResult exists and contains parameters, PrimaryKey is determined
        if ($routeResult) {
            $params = array_diff_key($routeResult->getMatchedParams() ?? [], ['middleware' => true]);
            if (!empty($params)) {
                $primaryKey = array_key_first($params);
            }
        }

        // JSON Body Parse
        $contentType = $headers['content-type'][0] ?? null;
        if ($contentType && str_starts_with($contentType, 'application/json')) {
            $contentBody = $request->getBody()->getContents();
            $parsedBody = json_decode($contentBody, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new BodyDecodeException($this->translator->translate(json_last_error_msg()));
            }
        } else {
            $parsedBody = $request->getParsedBody();
        }

        // Set Json Body
        if (in_array($request->getMethod(), ['POST', 'PUT', 'OPTIONS'], true)) {
            if ($primaryId = $request->getAttribute($primaryKey)) { // Primary ID settings
                $parsedBody[$primaryKey] = $primaryId;
            }
            $request = $request->withParsedBody($parsedBody);
        } else {
            $queryParams = $request->getQueryParams();
            if ($primaryId = $request->getAttribute($primaryKey)) { // Primary ID settings
                $queryParams[$primaryKey] = $primaryId;
            }
            $request = $request->withQueryParams($queryParams);
        }
        return $handler->handle($request);
    }
}
