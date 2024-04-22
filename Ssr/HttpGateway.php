<?php

namespace Rompetomp\InertiaBundle\Ssr;

use Exception;
use Rompetomp\InertiaBundle\Service\InertiaInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class HttpGateway implements GatewayInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private InertiaInterface    $inertia
    )
    {
    }

    /**
     * Dispatch the Inertia page to the Server Side Rendering engine.
     * @param array<array-key, mixed> $page
     * @return Response|null
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function dispatch(array $page): ?Response
    {
        try {
            $response = $this->httpClient->request(
                'POST',
                $this->inertia->getSsrUrl(),
                [
                    'headers' => [
                        'Content-Type: application/json',
                        'Accept: application/json',
                    ],
                    'body' => json_encode($page),
                ]
            );
        } catch (Exception) {
            return null;
        }

        $content = $response->toArray();

        return new Response(
            implode("\n", $content['head']),
            $content['body']
        );
    }
}
