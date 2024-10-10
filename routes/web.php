<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', 'admin', 301);
Route::get('/admin/request/requests/pdf/{id}', [\App\Http\Controllers\PdfController::class, 'index'])
    ->name('pdf');
