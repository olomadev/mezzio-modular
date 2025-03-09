<?php

declare(strict_types=1);

namespace Authentication\Handler;

use Exception;
use Authentication\Model\TokenModelInterface;
use Firebase\JWT\ExpiredException;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Olobase\Mezzio\Error\ErrorWrapperInterface as Error;
use Olobase\Mezzio\Authentication\JwtEncoderInterface as JwtEncoder;
use Mezzio\Authentication\AuthenticationInterface;
use Laminas\I18n\Translator\TranslatorInterface;

class RefreshHandler implements RequestHandlerInterface
{
    private $config;

    // Bu sinyal frontend tarafından kontrol ediliyor, değerini değiştirmeyin.
    protected const LOGOUT_SIGNAL = 'Logout';

    public function __construct(
        array $config,
        private TranslatorInterface $translator,
        private AuthenticationInterface $authentication,
        private TokenModelInterface $tokenModel,
        private Error $error
    ) {
        $this->config = $config;
    }

    /**
     * @OA\Post(
     *   path="/auth/refresh",
     *   tags={"Authentication"},
     *   summary="Refresh the token",
     *   operationId="auth_refresh",
     *
     *   @OA\RequestBody(
     *     description="Token refresh request",
     *     @OA\JsonContent(ref="#/components/schemas/RefreshToken"),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *     @OA\JsonContent(ref="#/components/schemas/AuthResult"),
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Unauthorized Response: token is expired"
     *   )
     * )
     **/
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $post = $request->getParsedBody();

        // don't change the these codes !
        if (empty($post['token'])) {
            return new JsonResponse(
                [
                    'data' => ['error' => self::LOGOUT_SIGNAL] // no token, exited
                ],
                401
            );
        }
        // token decryption
        $token = $this->tokenModel->getTokenEncrypt()->decrypt($post['token']);
        if (!$token) {
            return new JsonResponse(
                [
                    'data' => ['error' => self::LOGOUT_SIGNAL] // token is invalid
                ],
                401
            );
        }
        try {
            $this->tokenModel->decode($token); // token verification
        } catch (ExpiredException $e) {
            
            list($header, $payload, $signature) = explode(".", $token);
            $payload = json_decode(base64_decode($payload), true);

            if (json_last_error() != JSON_ERROR_NONE) {
                return new JsonResponse(
                    [
                        'data' => ['error' => $this->translator->translate("Invalid token")]
                    ],
                    401
                );
            }
            // token renewal process
            $data = $this->tokenModel->refresh($request, $payload);
            if (false == $data) {
                return new JsonResponse(
                    [
                        'data' => ['error' => self::LOGOUT_SIGNAL] // token could not be refreshed
                    ],
                    401
                );
            }
            $details = $data['data']['details']; // new token and user information
            return new JsonResponse(
                [
                    'data' => [
                        'token' => $data['token'],
                        'user'  => [
                            'id' => $details['id'],
                            'fullname' => $details['fullname'],
                            'email' => $details['email'],
                            'permissions' => $data['data']['roles'],
                        ],
                        'expiresAt' => $data['expiresAt'],
                    ],
                ]
            );
        } catch (Exception $e) {
            return new JsonResponse(
                [
                    'data' => ['error' => $e->getMessage()]
                ],
                401
            );
        }

        return new JsonResponse(
            [
                'data' => ['info' => $this->translator->translate("Token not expired to refresh")]
            ],
            401
        );
    }
}
