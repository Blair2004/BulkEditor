<?php
/**
 * Service Provider
 * @package : BulkEditor
**/
namespace Modules\BulkEditor\Providers;

use App\Classes\Hook;
use Illuminate\Support\ServiceProvider as CoreServiceProvider;
use Modules\BulkEditor\Services\ConfigurationService;

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
    }
    
    /**
     * Boot method
    **/
    public function boot()
    {
        // boot stuff here
    }
}