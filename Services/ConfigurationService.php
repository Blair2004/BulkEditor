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
        }
    }
}