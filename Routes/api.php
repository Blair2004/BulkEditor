<?php

use App\Http\Middleware\Authenticate;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Modules\BulkEditor\Http\Controllers\BulkEditorController;

Route::middleware([
    SubstituteBindings::class,
    Authenticate::class,
])->group(function () {
    Route::post( '/bulk-update', [ BulkEditorController::class, 'bulkEdit' ] );
});