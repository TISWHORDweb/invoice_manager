<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class InvoiceService
{
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = Invoice::query()->withCount('items', 'attachments');

        if (! empty($filters['date_from'])) {
            $query->whereDate('invoice_date', '>=', Carbon::parse($filters['date_from']));
        }
        if (! empty($filters['date_to'])) {
            $query->whereDate('invoice_date', '<=', Carbon::parse($filters['date_to']));
        }
        if (! empty($filters['payment_status']) && $filters['payment_status'] !== '') {
            $query->where('payment_status', $filters['payment_status']);
        }
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('client_name', 'like', "%{$search}%");
            });
        }

        $query->orderByDesc('invoice_date')->orderByDesc('id');

        return $query->paginate($perPage)->withQueryString();
    }

    public function create(array $data): Invoice
    {
        $invoice = new Invoice();
        $invoice->fill([
            'invoice_number' => $data['invoice_number'],
            'invoice_date' => $data['invoice_date'],
            'client_name' => $data['client_name'],
            'client_email' => $data['client_email'] ?? null,
            'client_address' => $data['client_address'] ?? null,
            'payment_status' => $data['payment_status'],
            'due_date' => $data['due_date'] ?? null,
            'tax_rate' => $data['tax_rate'] ?? 0,
            'notes' => $data['notes'] ?? null,
        ]);
        $invoice->save();

        $this->syncItems($invoice, $data['items'] ?? []);

        $invoice->load('items');
        $invoice->recalculateTotals();

        return $invoice;
    }

    public function update(Invoice $invoice, array $data): Invoice
    {
        $invoice->fill([
            'invoice_number' => $data['invoice_number'],
            'invoice_date' => $data['invoice_date'],
            'client_name' => $data['client_name'],
            'client_email' => $data['client_email'] ?? null,
            'client_address' => $data['client_address'] ?? null,
            'payment_status' => $data['payment_status'],
            'due_date' => $data['due_date'] ?? null,
            'tax_rate' => $data['tax_rate'] ?? 0,
            'notes' => $data['notes'] ?? null,
        ]);
        $invoice->save();

        $this->syncItems($invoice, $data['items'] ?? []);

        $invoice->load('items');
        $invoice->recalculateTotals();

        return $invoice;
    }

    public function delete(Invoice $invoice): bool
    {
        return $invoice->delete();
    }

    protected function syncItems(Invoice $invoice, array $items): void
    {
        $existingIds = [];
        $sortOrder = 0;

        foreach ($items as $row) {
            $item = null;
            if (! empty($row['id'])) {
                $item = InvoiceItem::where('invoice_id', $invoice->id)->find($row['id']);
            }
            if (! $item) {
                $item = new InvoiceItem(['invoice_id' => $invoice->id]);
            }
            $item->fill([
                'description' => $row['description'],
                'quantity' => (int) $row['quantity'],
                'unit_price' => (float) $row['unit_price'],
                'sort_order' => $sortOrder++,
            ]);
            $item->save();
            $existingIds[] = $item->id;
        }

        InvoiceItem::where('invoice_id', $invoice->id)->whereNotIn('id', $existingIds)->delete();
    }
}
