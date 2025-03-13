<?php

namespace Modules\BulkEditor\Listeners;

use App\Events\RenderCrudTableFooterEvent;
use App\Services\CrudService;

class RenderCrudTableFooterEventListener
{
    /**
     * Handle the event.
     */
    public function handle( RenderCrudTableFooterEvent $event )
    {
        if ( is_subclass_of( $event->instance, CrudService::class ) ) {
            $event->output->addView( 'BulkEditor::register-component' );
        }
    }
}
