@extends('layouts.app')

@section('title', 'Invoices – ' . config('app.name'))

@section('content')
<h1>Invoices</h1>

<form method="get" action="{{ route('invoices.index') }}" class="filter-form">
    <div>
        <label for="date_from">From date</label>
        <input type="date" id="date_from" name="date_from" value="{{ $filters['date_from'] ?? '' }}">
    </div>
    <div>
        <label for="date_to">To date</label>
        <input type="date" id="date_to" name="date_to" value="{{ $filters['date_to'] ?? '' }}">
    </div>
    <div>
        <label for="payment_status">Payment status</label>
        <select id="payment_status" name="payment_status">
            <option value="">All</option>
            @foreach(\App\Models\Invoice::paymentStatuses() as $value => $label)
                <option value="{{ $value }}" {{ ($filters['payment_status'] ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="search">Search</label>
        <input type="text" id="search" name="search" placeholder="Number or client" value="{{ $filters['search'] ?? '' }}">
    </div>
    <div>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('invoices.index') }}" class="btn">Clear</a>
    </div>
</form>

@if($invoices->isEmpty())
    <p>No invoices found. <a href="{{ route('invoices.create') }}">Create one</a>.</p>
@else
    <table>
        <thead>
            <tr>
                <th>Number</th>
                <th>Date</th>
                <th>Client</th>
                <th>Status</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->invoice_number }}</td>
                    <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                    <td>{{ $invoice->client_name }}</td>
                    <td>{{ \App\Models\Invoice::paymentStatuses()[$invoice->payment_status] ?? $invoice->payment_status }}</td>
                    <td>{{ number_format($invoice->total, 2) }}</td>
                    <td>
                        <a href="{{ route('invoices.show', $invoice) }}">View</a> |
                        <a href="{{ route('invoices.edit', $invoice) }}">Edit</a> |
                        <form method="post" action="{{ route('invoices.destroy', $invoice) }}" style="display:inline;" onsubmit="return confirm('Delete this invoice?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding:0.25rem 0.5rem;font-size:0.875rem;">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $invoices->links() }}
@endif
@endsection
