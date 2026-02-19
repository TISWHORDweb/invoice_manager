@extends('layouts.app')

@section('title', 'Edit ' . $invoice->invoice_number . ' – ' . config('app.name'))

@section('content')
<h1 class="page-title">Edit invoice {{ $invoice->invoice_number }}</h1>
<div class="card">
    @include('invoices._form', ['invoice' => $invoice, 'action' => route('invoices.update', $invoice), 'method' => 'PUT'])
</div>
<p style="margin-top:1rem;">
    <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-outline">Cancel</a>
</p>
@endsection
