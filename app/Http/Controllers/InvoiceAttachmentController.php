<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceAttachment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InvoiceAttachmentController extends Controller
{
    public function store(Request $request, Invoice $invoice): RedirectResponse
    {
        $this->authorize('view', $invoice);

        $maxKb = (int) env('UPLOAD_MAX_SIZE_KB', 5120);
        $valid = $request->validate([
            'file' => [
                'required',
                'file',
                'max:' . $maxKb,
                File::types(['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif']),
            ],
        ], [
            'file.required' => 'Please select a file to upload.',
            'file.max' => "The file must not be larger than {$maxKb} KB.",
        ]);

        $file = $request->file('file');
        $path = $file->store('invoice-attachments/' . $invoice->id, config('filesystems.default'));

        $invoice->attachments()->create([
            'original_name' => $file->getClientOriginalName(),
            'storage_path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'File uploaded successfully.');
    }

    public function download(Invoice $invoice, InvoiceAttachment $attachment): StreamedResponse|RedirectResponse
    {
        $this->authorize('view', $invoice);

        if ($attachment->invoice_id !== $invoice->id) {
            abort(404);
        }

        if (! Storage::disk($attachment->getDisk())->exists($attachment->storage_path)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        return Storage::disk($attachment->getDisk())->download(
            $attachment->storage_path,
            $attachment->original_name
        );
    }

    public function destroy(Invoice $invoice, InvoiceAttachment $attachment): RedirectResponse
    {
        $this->authorize('delete', $attachment);

        if ($attachment->invoice_id !== $invoice->id) {
            abort(404);
        }

        $attachment->deleteFileFromStorage();
        $attachment->delete();

        return redirect()
            ->back()
            ->with('success', 'Attachment removed.');
    }
}
