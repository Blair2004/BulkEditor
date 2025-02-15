<?php

/**
 * Bulk Editor Controller
 * @since 1.0
 * @package modules/BulkEditor
**/

namespace Modules\BulkEditor\Http\Controllers;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\BulkEditor\Services\BulkEditService;

class BulkEditorController extends Controller
{
    public function __construct( public BulkEditService $bulkEditService )
    {
        // ...
    }
    
    public function bulkEdit( Request $request )
    {
        return $this->bulkEditService->update( 
            class: $request->input( 'class' ), 
            ids: $request->input( 'selected' ), 
            data: $request->input( 'fields' )
        );
    }
}
