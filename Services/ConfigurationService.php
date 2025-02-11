<?php
namespace Modules\BulkEditor\Services;

class ConfigurationService
{
    private $declarations   =   [];

    public function register( string $class, array $configuration )
    {
        $this->declarations[$class] = $configuration;
    }
}