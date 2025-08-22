<?php
/**
 * Service Provider
 * @package : BulkEditor
**/
namespace Modules\BulkEditor\Providers;

use App\Classes\Hook;
use App\Services\ModulesService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider as CoreServiceProvider;
use Modules\BulkEditor\Services\BulkEditService;
use Modules\BulkEditor\Services\ConfigurationService;
use Modules\NsMultiStore\Events\MultiStoreApiRoutesLoadedEvent;
use Modules\NsMultiStore\Events\MultiStoreWebRoutesLoadedEvent;

class ServiceProvider extends CoreServiceProvider
{
    /**
     * register method
     */
    public function register()
    {
        $this->app->singleton( ConfigurationService::class, function() {
            return new ConfigurationService;
        });

        $this->app->singleton( BulkEditService::class, function() {
            return new BulkEditService(
                $this->app->make( ConfigurationService::class )
            );
        });

        Event::listen(MultiStoreApiRoutesLoadedEvent::class, fn () => ModulesService::loadModuleFile('BulkEditor', 'Routes/api'));
    }
    
    /**
     * Boot method
    **/
    public function boot()
    {
        // boot stuff here
    }
}