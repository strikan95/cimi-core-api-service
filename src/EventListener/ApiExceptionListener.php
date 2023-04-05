<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        // Check if it is a rest api request
        if ('application/json' === $event->getRequest()->headers->get('Content-Type'))
        {
            $event->setResponse(
                $this->createJsonExceptionResponse($event)
            );
        }
    }

    private function createJsonExceptionResponse(ExceptionEvent $event): Response
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();
        $response = new Response();

        // Customize your response object to display the exception details
        $content =
            [
                'message'       => $exception->getMessage(),
                'code'          => $exception->getCode(),
                'traces'        => $exception->getTrace()
            ];

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface)
        {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        }
        else
        {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $response->setContent(json_encode($content));
        $response->headers->add(['Content-Type' => 'application/json']);
        return $response;
    }
}