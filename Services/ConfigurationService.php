<?php
namespace Modules\BulkEditor\Services;

use App\Classes\Hook;
use App\Services\CrudService;

class ConfigurationService
{
    private $declarations   =   [];

    public function register( string $class, array $configuration )
    {
        $this->declarations[$class] = $configuration;

        // yet here, we'll register a filter that will mutate the header
        // buttons for the Crud class provided.
        if ( is_subclass_of( $class, CrudService::class ) ) {
            $class::filterMethod( 'getHeaderButtons', function( $buttons ) {
                $buttons[]  =   'nsBulkEditor';
                return $buttons;
            });

            $class::filterMethod( 'getCrudConfig', function( $config ) use ( $class ) {
                $config[ 'bulkEditConfig' ]     =   array_merge( $this->declarations[ $class ], [ 'class' => $class ] );

                // this will hide the default button that shows selected entries
                $config[ 'showSelectedEntries' ] =  false;

                return $config;
            });
        }
    }

    public function getConfiguration( string $class )
    {
        return $this->declarations[ $class ] ?? null;
    }
}