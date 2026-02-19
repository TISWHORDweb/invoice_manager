@extends('layouts.app')

@section('title', 'Invoices – ' . config('app.name'))

@section('content')
<h1 class="page-title">Invoices</h1>

<form method="get" action="{{ route('invoices.index') }}" class="filter-bar">
    <div class="field">
        <label for="date_from">From date</label>
        <input type="date" id="date_from" name="date_from" value="{{ $filters['date_from'] ?? '' }}">
    </div>
    <div class="field">
        <label for="date_to">To date</label>
        <input type="date" id="date_to" name="date_to" value="{{ $filters['date_to'] ?? '' }}">
    </div>
    <div class="field">
        <label for="payment_status">Status</label>
        <select id="payment_status" name="payment_status">
            <option value="">All</option>
            @foreach(\App\Models\Invoice::paymentStatuses() as $value => $label)
                <option value="{{ $value }}" {{ ($filters['payment_status'] ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="field" style="min-width: 180px;">
        <label for="search">Search</label>
        <input type="text" id="search" name="search" placeholder="Number or client" value="{{ $filters['search'] ?? '' }}">
    </div>
    <div class="actions">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('invoices.index') }}" class="btn btn-outline">Clear</a>
    </div>
</form>

@if($invoices->isEmpty())
    <div class="empty-state card">
        <p>No invoices match your criteria.</p>
        <a href="{{ route('invoices.create') }}" class="btn btn-primary">Create your first invoice</a>
    </div>
@else
    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Number</th>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Status</th>
                        <th class="text-right">Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                        <tr>
                            <td><strong>{{ $invoice->invoice_number }}</strong></td>
                            <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                            <td>{{ $invoice->client_name }}</td>
                            <td><span class="badge badge-{{ $invoice->payment_status }}">{{ \App\Models\Invoice::paymentStatuses()[$invoice->payment_status] ?? $invoice->payment_status }}</span></td>
                            <td class="text-right num">{{ number_format($invoice->total, 2) }}</td>
                            <td class="actions-cell">
                                <a href="{{ route('invoices.show', $invoice) }}" class="icon-btn" title="View" aria-label="View invoice">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                <a href="{{ route('invoices.edit', $invoice) }}" class="icon-btn" title="Edit" aria-label="Edit invoice">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                <form method="post" action="{{ route('invoices.destroy', $invoice) }}" class="icon-btn-form" onsubmit="return confirm('Delete this invoice?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="icon-btn icon-btn-danger" title="Delete" aria-label="Delete invoice">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $invoices->links() }}
    </div>
@endif
@endsection
