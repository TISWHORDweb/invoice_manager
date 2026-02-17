@extends('layouts.app')

@section('title', 'Invoice ' . $invoice->invoice_number . ' – ' . config('app.name'))

@section('content')
<h1>Invoice {{ $invoice->invoice_number }}</h1>
<p><strong>Date:</strong> {{ $invoice->invoice_date->format('Y-m-d') }} |
   <strong>Client:</strong> {{ $invoice->client_name }} |
   <strong>Status:</strong> {{ \App\Models\Invoice::paymentStatuses()[$invoice->payment_status] ?? $invoice->payment_status }}</p>
<p><strong>Total:</strong> {{ number_format($invoice->total, 2) }}</p>

<h2>Line items</h2>
<table>
    <thead>
        <tr><th>Description</th><th>Qty</th><th>Unit price</th><th>Amount</th></tr>
    </thead>
    <tbody>
        @foreach($invoice->items as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->unit_price, 2) }}</td>
                <td>{{ number_format($item->amount, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<h2>Attachments</h2>
@if($invoice->attachments->isEmpty())
    <p>No attachments. Upload a file below.</p>
@else
    <ul>
        @foreach($invoice->attachments as $att)
            <li>
                <a href="{{ route('invoices.attachments.download', [$invoice, $att]) }}">{{ $att->original_name }}</a>
                ({{ number_format($att->size / 1024, 1) }} KB)
                <form method="post" action="{{ route('invoices.attachments.destroy', [$invoice, $att]) }}" style="display:inline;" onsubmit="return confirm('Remove this file?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="padding:0.2rem 0.4rem;font-size:0.75rem;">Remove</button>
                </form>
            </li>
        @endforeach
    </ul>
@endif

<form method="post" action="{{ route('invoices.attachments.store', $invoice) }}" enctype="multipart/form-data" style="margin-top:0.5rem;">
    @csrf
    <input type="file" name="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif" required>
    <button type="submit" class="btn btn-primary">Upload</button>
</form>

<p style="margin-top:1.5rem;">
    <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-primary">Edit invoice</a>
    <a href="{{ route('invoices.index') }}" class="btn">Back to list</a>
</p>
@endsection
