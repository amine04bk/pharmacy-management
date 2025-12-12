<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

class DatabaseSSLManager
{
    private ?string $certPath = null;

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private string $projectDir,
        #[Autowire('%env(default::DATABASE_SSL_CA)%')]
        private string $sslCert
    ) {
    }

    public function getCertificatePath(): string
    {
        if ($this->certPath && file_exists($this->certPath)) {
            return $this->certPath;
        }

        // If DATABASE_SSL_CA is a file path, use it directly
        if (file_exists($this->sslCert)) {
            $this->certPath = $this->sslCert;
            return $this->certPath;
        }

        // Otherwise, write certificate content to a temporary file
        $certDir = $this->projectDir . '/var/ssl';
        if (!is_dir($certDir)) {
            @mkdir($certDir, 0755, true);
        }

        $this->certPath = $certDir . '/ca.pem';
        
        // Write certificate if it contains PEM data
        if (str_contains($this->sslCert, 'BEGIN CERTIFICATE')) {
            file_put_contents($this->certPath, $this->sslCert);
            chmod($this->certPath, 0644);
        }

        return $this->certPath;
    }

    public function hasCertificate(): bool
    {
        return !empty($this->sslCert) && $this->sslCert !== 'default::DATABASE_SSL_CA';
    }
}
