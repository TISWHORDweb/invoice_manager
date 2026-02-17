@extends('layouts.app')

@section('title', 'New invoice – ' . config('app.name'))

@section('content')
<h1>New invoice</h1>
@include('invoices._form', ['invoice' => $invoice, 'action' => route('invoices.store'), 'method' => 'POST'])
<p><a href="{{ route('invoices.index') }}" class="btn">Cancel</a></p>
@endsection
