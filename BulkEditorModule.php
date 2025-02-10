<?php
namespace Modules\BulkEditor;

use Illuminate\Support\Facades\Event;
use App\Services\Module;

class BulkEditorModule extends Module
{
    public function __construct()
    {
        parent::__construct( __FILE__ );
    }
}