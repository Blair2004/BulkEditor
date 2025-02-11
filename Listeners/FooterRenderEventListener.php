<?php

namespace Modules\BulkEditor\Listeners;

use App\Events\FooterRenderEvent;
use App\Services\CrudService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class FooterRenderEventListener
{
    /**
     * Handle the event.
     */
    public function handle( FooterRenderEvent $event )
    {
        if ( is_subclass_of( $event->class, CrudService::class ) ) {
            $event->output->addView( 'BulkEditor::register-component' );
        }
    }
}
