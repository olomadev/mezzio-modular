<?php

declare(strict_types=1);

namespace Common\Middleware;

use Mezzio\Router\RouteResult;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\JsonResponse;
use Olobase\Mezzio\Exception\BodyDecodeException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\I18n\Translator\TranslatorInterface;

class JsonBodyParserMiddleware implements MiddlewareInterface
{
    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $headers = $request->getHeaders();
        $server  = $request->getServerParams();
        $routeResult = $request->getAttribute(RouteResult::class, false);
        //
        // Sets "primary id" if it's exists
        // 
        $primaryKey = "null";
        if ($routeResult) {
            $params = $routeResult->getMatchedParams();
            if (is_array($params) && ! empty($params)) {
                unset($params['middleware']);
                $paramArray = array_keys($params);
                $primaryKey = empty($paramArray[0]) ? "null" : trim((string)$paramArray[0]);
            }  
        }
        //
        // Sets http method
        // 
        define('HTTP_METHOD', $request->getMethod());
        //
        // Parses & sets json content to request body
        //
        $get = array();
        $post = array();
        $contentType = empty($headers['content-type'][0]) ? null : current($headers['content-type']);
        if ($contentType 
            && strpos($contentType, 'application/json') === 0) {
            $contentBody = $request->getBody()->getContents();
            $post = json_decode($contentBody, true);
            $lastError = json_last_error();
            if ($lastError != JSON_ERROR_NONE) {
                throw new BodyDecodeException($this->translator->translate($lastError));
            }
        }
        // Set $primaryKey as "id"
        //
        switch ($request->getMethod()) {
            case 'POST':
            case 'PUT':
            case 'OPTIONS':
                $post = empty($post) ? $request->getParsedBody() : $post;
                if ($primaryId = $request->getAttribute($primaryKey)) {
                    $post['id'] = $primaryId;
                }
                $request = $request->withParsedBody($post);
                break;
            case 'PATCH':
            case 'HEAD':
            case 'GET':
            case 'TRACE':
            case 'CONNECT':
            case 'DELETE':
            case 'PROPFIND': // PROPFIND — used to retrieve properties, stored as XML, from a web resource.
                $get = $request->getQueryParams();
                if ($primaryId = $request->getAttribute($primaryKey)) {
                    $get['id'] = $primaryId;
                    $request = $request->withQueryParams($get);
                }
                break;
        }
        return $handler->handle($request);
    }
}
