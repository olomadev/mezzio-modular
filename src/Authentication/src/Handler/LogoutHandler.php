<?php

declare(strict_types=1);

namespace Authentication\Handler;

use Exception;
use Firebase\JWT\ExpiredException;
use Authentication\Model\TokenModelInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\I18n\Translator\TranslatorInterface;

class LogoutHandler implements RequestHandlerInterface
{
    public function __construct(
        private TranslatorInterface $translator,
        private TokenModelInterface $tokenModel
    ) {
        $this->translator = $translator;
        $this->tokenModel = $tokenModel;
    }

    /**
     * @OA\Get(
     *   path="/auth/logout",
     *   tags={"Authentication"},
     *   summary="Logout the user",
     *   operationId="auth_logout",
     *
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *   )
     *)
     **/
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // user token may expired thats why it's 
        // important manually extract the token from header 
        //
        $token = null;
        $authHeader = $request->getHeader('Authorization');
        if (empty($authHeader)) {
            $token = null;
        } else if (preg_match("/Bearer\s+(.*)$/i", $authHeader[0], $matches)) {
            $token = $matches[1];
        }
        if (empty($token)) {
            return new JsonResponse(
                [
                    'data' => [
                        'error' => $this->translator->translate("Invalid token")
                    ]
                ], 
                401
            );
        }
        $token = $this->tokenModel->getTokenEncrypt()->decrypt($token);
        try {
            $data = $this->tokenModel->decode($token);
            if (! empty($data['data']->details->id)) {
                $this->tokenModel->kill( // delete the user from session db
                    $data['data']->details->id,
                    $data['data']->details->tokenId
                ); 
            }
        } catch (ExpiredException $e) {
            
            list($header, $payload, $signature) = explode(".", $token);
            $base64DecodedToken = base64_decode($payload);
            $token = json_decode($base64DecodedToken, true);

            if (json_last_error() != JSON_ERROR_NONE) {
                return new JsonResponse(
                    [
                        'data' => [
                            'error' => $this->translator->translate("Invalid token")
                        ]
                    ], 
                    401
                );
            }
            if ($token) {
                $this->tokenModel->kill( // delete the user from session db
                    $token['data']['details']['id'],
                    $token['data']['details']['tokenId']
                );
            }
        } catch (Exception $e) {
            return new JsonResponse(
                [
                    'data' => [
                        'error' => $e->getMessage()
                    ]
                ], 
                401
            );
        }
        return new JsonResponse([]);
    }

}
