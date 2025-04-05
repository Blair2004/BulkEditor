<?php
namespace Modules\BulkEditor\Contracts;

use Modules\BulkEditor\Services\ConfigurationService;

interface BulkEditorConfiguration
{
    public function setup( ConfigurationService $configurationService ): void;
}