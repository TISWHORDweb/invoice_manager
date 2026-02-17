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

<p style="margin-top:1.5rem;">
    <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-primary">Edit invoice</a>
    <a href="{{ route('invoices.index') }}" class="btn">Back to list</a>
</p>
@endsection
