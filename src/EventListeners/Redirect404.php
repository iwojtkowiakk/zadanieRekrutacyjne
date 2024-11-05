<?php

namespace App\EventListeners;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class Redirect404
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        if ($event->getThrowable()->getCode() === 404) {
            return;
        }

        $response = new RedirectResponse($this->router->generate('warehouse_all'));

        $event->setResponse($response);
    }
}