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
    public function __construct(
        private array $config,
        private TranslatorInterface $translator
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $locale = $request->getHeaderLine('x-client-locale');
        $acceptedLocales = $this->config['translator']['locale'] ?? ['en']; // Default language is "en"

        // If the incoming locale is among the supported languages, use it, otherwise use the fallback language
        $langId = in_array($locale, $acceptedLocales, true) ? $locale : $acceptedLocales[0];

        // Set language to Translator
        $this->translator->setLocale($langId);

        // Add the language to the Request object and pass it to the handler
        return $handler->handle($request->withAttribute('lang_id', $langId));
    }
}
