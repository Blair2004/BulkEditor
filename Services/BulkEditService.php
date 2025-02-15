<?php
namespace Modules\BulkEditor\Services;

class BulkEditService
{
    public function __construct( public ConfigurationService $configurationService )
    {
        // ...
    }

    public function update( string $class, array $ids, array $data )
    {
        $crudInstance   =   new $class;
        $model          =   $crudInstance->getModel();
        $configuration  =   $this->configurationService->getConfiguration( $class );
        $mapping        =   $configuration[ 'mapping' ];

        // we need to filter data to only include fields
        // that are set
        $data   =   array_filter( $data, function( $value ) {
            return !is_null( $value );
        });

        $model::whereIn( $mapping[ 'value' ], $ids )->update( $data );

        return [
            'status'    =>  'success',
            'message'   =>  'Entries updated successfully.'
        ];
    }
}