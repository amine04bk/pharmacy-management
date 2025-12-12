<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function boot(): void
    {
        parent::boot();
        
        // Create SSL certificate file from environment variable if it exists
        $sslCert = $_ENV['DATABASE_SSL_CA'] ?? $_SERVER['DATABASE_SSL_CA'] ?? null;
        
        if ($sslCert && str_contains($sslCert, 'BEGIN CERTIFICATE')) {
            $certDir = $this->getProjectDir() . '/var/ssl';
            if (!is_dir($certDir)) {
                @mkdir($certDir, 0755, true);
            }
            
            $certPath = $certDir . '/ca.pem';
            if (!file_exists($certPath) || file_get_contents($certPath) !== $sslCert) {
                file_put_contents($certPath, $sslCert);
                @chmod($certPath, 0644);
            }
        }
    }
}
