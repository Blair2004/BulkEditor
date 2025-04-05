<?php
namespace Modules\BulkEditor\Listeners;

use App\Events\CrudReflectionInitialized;
use Modules\BulkEditor\Classes\BulkEdit;
use Modules\BulkEditor\Services\ConfigurationService;

class CrudReflectionInitializedListener
{
    public function handle( CrudReflectionInitialized $event )
    {
        $attributes     =   $event->reflection->getAttributes();

        // We'll initialy loop over the attributes to find the BulkEdit attribute
        // and then we'll check if the config class exists and if it does, we'll
        // create an instance of it and call the setup method if it exists.
        foreach( $attributes as $attribute ) {
            if ( $attribute->getName() === BulkEditor::class ) {
                $arguments = $attribute->getArguments();

                if ( isset( $arguments[ 'config' ] ) && class_exists( $arguments[ 'config' ] ) ) {
                    $instance = new $arguments[ 'config' ];
                    
                    if ( method_exists( $instance, 'setup' ) ) {
                        $instance->setup( app()->make( ConfigurationService::class ) );
                    }
                }
            }            
        }
    }
}