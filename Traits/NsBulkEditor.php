<?php
namespace Modules\BulkEditor\Traits;

Trait NsBulkEditor {
    protected $bulkbulkEditConfigurationEdit     =   [];

    public function bulkEdit( string $model, array $fields )
    {
        $this->bulkEditConfiguration     =   [
            'model'     =>  $model,
            'fields'    =>  $fields
        ];
    }
}