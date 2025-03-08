<?php

declare(strict_types=1);

namespace Common\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\I18n\Translator\TranslatorInterface;

class SetLocaleMiddleware implements MiddlewareInterface
{    
    private $config;

    public function __construct(
        array $config,
        private TranslatorInterface $translator
    )
    {
        $this->config = $config;
        $this->translator = $translator;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $headers = $request->getHeaders();
        // 
        // Sets language (don't change below the lines: front end 
        // application sends current language in http header)
        //
        $langId = null; // fallback language
        if (! empty($headers['x-client-locale'][0])) {
            $locale = $headers['x-client-locale'][0];
            if ($locale 
                && in_array(
                    $locale,
                    $this->config['translator']['locale'] // accepted languages
                )
            ) {
                $langId = $locale;
            }
        }
        if ($langId) {
            define('LANG_ID', $langId);
            $this->translator->setLocale($langId);
        }
        return $handler->handle($request);
    }
}
