@extends('layouts.app')

@section('title', 'New invoice – ' . config('app.name'))

@section('content')
<h1 class="page-title">New invoice</h1>
<div class="card">
    @include('invoices._form', ['invoice' => $invoice, 'action' => route('invoices.store'), 'method' => 'POST'])
</div>
<p style="margin-top:1rem;">
    <a href="{{ route('invoices.index') }}" class="btn btn-outline">Cancel</a>
</p>
@endsection
