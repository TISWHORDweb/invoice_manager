<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $invoices = [
            [
                'invoice_number' => 'INV-2025-001',
                'invoice_date' => now()->subDays(10),
                'client_name' => 'Acme Corp',
                'client_email' => 'billing@acme.example',
                'client_address' => '123 Main St, City',
                'payment_status' => Invoice::PAYMENT_STATUS_PAID,
                'due_date' => now()->subDays(3),
                'tax_rate' => 10,
                'notes' => 'Thank you for your business.',
                'items' => [
                    ['description' => 'Consulting services', 'quantity' => 10, 'unit_price' => 150.00],
                    ['description' => 'Support package', 'quantity' => 1, 'unit_price' => 500.00],
                ],
            ],
            [
                'invoice_number' => 'INV-2025-002',
                'invoice_date' => now()->subDays(5),
                'client_name' => 'TechStart Ltd',
                'client_email' => 'accounts@techstart.example',
                'client_address' => '45 Innovation Way',
                'payment_status' => Invoice::PAYMENT_STATUS_PENDING,
                'due_date' => now()->addDays(25),
                'tax_rate' => 0,
                'notes' => null,
                'items' => [
                    ['description' => 'Software license', 'quantity' => 5, 'unit_price' => 299.00],
                ],
            ],
            [
                'invoice_number' => 'INV-2025-003',
                'invoice_date' => now()->subDays(45),
                'client_name' => 'Global Solutions',
                'client_email' => null,
                'client_address' => null,
                'payment_status' => Invoice::PAYMENT_STATUS_OVERDUE,
                'due_date' => now()->subDays(15),
                'tax_rate' => 20,
                'notes' => 'Please remit payment at your earliest convenience.',
                'items' => [
                    ['description' => 'Design work', 'quantity' => 20, 'unit_price' => 75.00],
                    ['description' => 'Revision round', 'quantity' => 2, 'unit_price' => 200.00],
                ],
            ],
        ];

        foreach ($invoices as $data) {
            $items = $data['items'];
            unset($data['items']);

            $invoice = Invoice::create($data);

            foreach ($items as $i => $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'sort_order' => $i,
                ]);
            }

            $invoice->load('items');
            $invoice->recalculateTotals();
        }
    }
}
