<?php

use App\Http\Controllers\InvoiceAttachmentController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('invoices.index');
});

Route::resource('invoices', InvoiceController::class);

Route::post('invoices/{invoice}/attachments', [InvoiceAttachmentController::class, 'store'])
    ->name('invoices.attachments.store');
Route::get('invoices/{invoice}/attachments/{attachment}/download', [InvoiceAttachmentController::class, 'download'])
    ->name('invoices.attachments.download');
Route::delete('invoices/{invoice}/attachments/{attachment}', [InvoiceAttachmentController::class, 'destroy'])
    ->name('invoices.attachments.destroy');
