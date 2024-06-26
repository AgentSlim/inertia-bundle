<?php

namespace Rompetomp\InertiaBundle\EventListener;

use Rompetomp\InertiaBundle\Service\InertiaInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

/**
 * Class InertiaListener.
 */
class InertiaListener
{
    public function __construct(
        protected InertiaInterface $inertia,
        protected bool $debug
    )
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (!$request->headers->get('X-Inertia')) {
            return;
        }

        if ('GET' === $request->getMethod()
            && $request->headers->get('X-Inertia-Version') !== $this->inertia->getVersion()
        ) {
            $response = new Response('', 409, ['X-Inertia-Location' => $request->getUri()]);
            $event->setResponse($response);
        }
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->getRequest()->headers->get('X-Inertia')) {
            return;
        }

        if ($this->debug && $event->getRequest()->isXmlHttpRequest()) {
            $event->getResponse()->headers->set('Symfony-Debug-Toolbar-Replace', 1);
        }

        if ($event->getResponse()->isRedirect()
            && 302 === $event->getResponse()->getStatusCode()
            && in_array($event->getRequest()->getMethod(), ['PUT', 'PATCH', 'DELETE'])
        ) {
            $event->getResponse()->setStatusCode(303);
        }
    }
}
