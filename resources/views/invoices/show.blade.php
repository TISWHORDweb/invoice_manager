@extends('layouts.app')

@section('title', 'Invoice ' . $invoice->invoice_number . ' – ' . config('app.name'))

@section('content')
<h1 class="page-title">Invoice {{ $invoice->invoice_number }}</h1>

<div class="card">
    <div class="card-header">Details</div>
    <div class="form-row" style="margin-bottom: 0;">
        <div class="form-group">
            <label>Date</label>
            <p style="margin:0;font-size:1rem;">{{ $invoice->invoice_date->format('Y-m-d') }}</p>
        </div>
        <div class="form-group">
            <label>Due date</label>
            <p style="margin:0;font-size:1rem;">{{ $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '—' }}</p>
        </div>
        <div class="form-group">
            <label>Payment status</label>
            <p style="margin:0;"><span class="badge badge-{{ $invoice->payment_status }}">{{ \App\Models\Invoice::paymentStatuses()[$invoice->payment_status] ?? $invoice->payment_status }}</span></p>
        </div>
        <div class="form-group">
            <label>Total</label>
            <p style="margin:0;font-size:1.25rem;font-weight:600;color:var(--accent);">{{ number_format($invoice->total, 2) }}</p>
        </div>
    </div>
    <div class="form-group">
        <label>Client</label>
        <p style="margin:0;">{{ $invoice->client_name }}</p>
        @if($invoice->client_email)
            <p style="margin:0.25rem 0 0 0;color:var(--text-muted);font-size:0.9rem;">{{ $invoice->client_email }}</p>
        @endif
        @if($invoice->client_address)
            <p style="margin:0.25rem 0 0 0;color:var(--text-muted);font-size:0.9rem;">{{ $invoice->client_address }}</p>
        @endif
    </div>
    @if($invoice->notes)
        <div class="form-group">
            <label>Notes</label>
            <p style="margin:0;color:var(--text-muted);">{{ $invoice->notes }}</p>
        </div>
    @endif
</div>

<div class="card">
    <div class="card-header">Line items</div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Description</th><th class="text-right">Qty</th><th class="text-right">Unit price</th><th class="text-right">Amount</th></tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                    <tr>
                        <td>{{ $item->description }}</td>
                        <td class="text-right num">{{ $item->quantity }}</td>
                        <td class="text-right num">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right num">{{ number_format($item->amount, 2) }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="3">Subtotal</td>
                    <td class="text-right num">{{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
                @if($invoice->tax_rate > 0)
                    <tr class="total-row">
                        <td colspan="3">Tax ({{ number_format($invoice->tax_rate, 1) }}%)</td>
                        <td class="text-right num">{{ number_format($invoice->tax_amount, 2) }}</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td colspan="3">Total</td>
                    <td class="text-right num">{{ number_format($invoice->total, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header">Attachments</div>
    @if($invoice->attachments->isEmpty())
        <p style="color:var(--text-muted);margin:0 0 1rem 0;">No attachments yet.</p>
    @else
        <ul class="attach-list">
            @foreach($invoice->attachments as $att)
                <li>
                    <a href="{{ route('invoices.attachments.download', [$invoice, $att]) }}">{{ $att->original_name }}</a>
                    <span class="size">{{ number_format($att->size / 1024, 1) }} KB</span>
                    <form method="post" action="{{ route('invoices.attachments.destroy', [$invoice, $att]) }}" class="icon-btn-form" onsubmit="return confirm('Remove this file?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="icon-btn icon-btn-danger" title="Remove file" aria-label="Remove file">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                        </button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
    <form method="post" action="{{ route('invoices.attachments.store', $invoice) }}" enctype="multipart/form-data" class="upload-zone">
        @csrf
        <input type="file" name="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif" required>
        <button type="submit" class="btn btn-primary">Upload file</button>
    </form>
</div>

<p style="margin-top:1.5rem;display:flex;gap:0.75rem;flex-wrap:wrap;">
    <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-primary">Edit invoice</a>
    <a href="{{ route('invoices.index') }}" class="btn btn-outline">Back to list</a>
</p>
@endsection
