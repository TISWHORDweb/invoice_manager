@extends('layouts.app')

@section('title', 'Edit ' . $invoice->invoice_number . ' – ' . config('app.name'))

@section('content')
<h1>Edit invoice {{ $invoice->invoice_number }}</h1>
@include('invoices._form', ['invoice' => $invoice, 'action' => route('invoices.update', $invoice), 'method' => 'PUT'])
<p><a href="{{ route('invoices.show', $invoice) }}" class="btn">Cancel</a></p>
@endsection
