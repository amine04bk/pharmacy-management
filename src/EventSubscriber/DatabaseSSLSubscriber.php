<?php

namespace App\EventSubscriber;

use App\Service\DatabaseSSLManager;
use Doctrine\Common\EventSubscriber;
use Doctrine\DBAL\Event\ConnectionEventArgs;
use Doctrine\DBAL\Events;

class DatabaseSSLSubscriber implements EventSubscriber
{
    public function __construct(
        private DatabaseSSLManager $sslManager
    ) {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postConnect,
        ];
    }

    public function postConnect(ConnectionEventArgs $args): void
    {
        if (!$this->sslManager->hasCertificate()) {
            return;
        }

        $connection = $args->getConnection();
        $params = $connection->getParams();

        // Add SSL options if not already set
        if (!isset($params['driverOptions'][\PDO::MYSQL_ATTR_SSL_CA])) {
            $certPath = $this->sslManager->getCertificatePath();
            
            $connection->getWrappedConnection()->setAttribute(
                \PDO::MYSQL_ATTR_SSL_CA,
                $certPath
            );
            $connection->getWrappedConnection()->setAttribute(
                \PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT,
                false
            );
        }
    }
}
