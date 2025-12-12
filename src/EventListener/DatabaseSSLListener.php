<?php

namespace App\EventListener;

use App\Service\DatabaseSSLManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class DatabaseSSLListener implements EventSubscriberInterface
{
    private static bool $initialized = false;

    public function __construct(
        private DatabaseSSLManager $sslManager
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 1024],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (self::$initialized || !$event->isMainRequest()) {
            return;
        }

        if ($this->sslManager->hasCertificate()) {
            // Ensure certificate file is created before any database connection
            $this->sslManager->getCertificatePath();
        }

        self::$initialized = true;
    }
}
