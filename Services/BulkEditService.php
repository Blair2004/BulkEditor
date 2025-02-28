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

        // We should check if the user is allowed
        // to update the fields provided
        $permission     =   $crudInstance->getPermission( 'update' );

        // if the user is allowed to update the fields
        // we'll update the entries
        if ( ns()->restrict( $permission ) ) {
            $model::whereIn( $mapping[ 'value' ], $ids )->update( $data );
    
            return [
                'status'    =>  'success',
                'message'   =>  __m( 'The selected entries have been updated.', 'NsRawMaterial' )
            ];
        }
    }
}