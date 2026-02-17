<form method="post" action="{{ $action }}" id="invoice-form">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
        <div>
            <label for="invoice_number">Invoice number *</label>
            <input type="text" id="invoice_number" name="invoice_number" value="{{ old('invoice_number', $invoice->invoice_number) }}" required>
        </div>
        <div>
            <label for="invoice_date">Invoice date *</label>
            <input type="date" id="invoice_date" name="invoice_date" value="{{ old('invoice_date', $invoice->invoice_date?->format('Y-m-d')) }}" required>
        </div>
    </div>
    <div style="margin-bottom:1rem;">
        <label for="client_name">Client name *</label>
        <input type="text" id="client_name" name="client_name" value="{{ old('client_name', $invoice->client_name) }}" style="width:100%;max-width:400px;" required>
    </div>
    <div style="margin-bottom:1rem;">
        <label for="client_email">Client email</label>
        <input type="email" id="client_email" name="client_email" value="{{ old('client_email', $invoice->client_email) }}" style="width:100%;max-width:400px;">
    </div>
    <div style="margin-bottom:1rem;">
        <label for="client_address">Client address</label>
        <textarea id="client_address" name="client_address" rows="2" style="width:100%;max-width:400px;">{{ old('client_address', $invoice->client_address) }}</textarea>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
        <div>
            <label for="payment_status">Payment status *</label>
            <select id="payment_status" name="payment_status" required>
                @foreach(\App\Models\Invoice::paymentStatuses() as $value => $label)
                    <option value="{{ $value }}" {{ old('payment_status', $invoice->payment_status) === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="due_date">Due date</label>
            <input type="date" id="due_date" name="due_date" value="{{ old('due_date', $invoice->due_date?->format('Y-m-d')) }}">
        </div>
    </div>
    <div style="margin-bottom:1rem;">
        <label for="tax_rate">Tax rate %</label>
        <input type="number" id="tax_rate" name="tax_rate" step="0.01" min="0" max="100" value="{{ old('tax_rate', $invoice->tax_rate ?? 0) }}">
    </div>
    <div style="margin-bottom:1rem;">
        <label for="notes">Notes</label>
        <textarea id="notes" name="notes" rows="3" style="width:100%;max-width:500px;">{{ old('notes', $invoice->notes) }}</textarea>
    </div>

    <h3>Line items</h3>
    <table id="items-table">
        <thead>
            <tr>
                <th>Description *</th>
                <th>Qty *</th>
                <th>Unit price *</th>
                <th>Amount</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @php $items = old('items', $invoice->items->isEmpty() ? [['description' => '', 'quantity' => 1, 'unit_price' => 0]] : $invoice->items->map(fn($i) => ['id' => $i->id, 'description' => $i->description, 'quantity' => $i->quantity, 'unit_price' => $i->unit_price])->toArray()); @endphp
            @foreach($items as $idx => $item)
                <tr>
                    <td><input type="text" name="items[{{ $idx }}][description]" value="{{ $item['description'] ?? '' }}" required></td>
                    <td><input type="number" name="items[{{ $idx }}][quantity]" min="1" value="{{ $item['quantity'] ?? 1 }}" class="item-qty" required></td>
                    <td><input type="number" name="items[{{ $idx }}][unit_price]" step="0.01" min="0" value="{{ $item['unit_price'] ?? 0 }}" class="item-price" required></td>
                    <td class="item-amount">0.00</td>
                    <td><button type="button" class="btn remove-row">Remove</button></td>
                    @if(!empty($item['id']))
                        <input type="hidden" name="items[{{ $idx }}][id]" value="{{ $item['id'] }}">
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    <button type="button" id="add-row" class="btn">Add line</button>

    <p style="margin-top:1rem;">
        <button type="submit" class="btn btn-primary">Save invoice</button>
    </p>
</form>

<script>
(function() {
    const tbody = document.querySelector('#items-table tbody');
    const addBtn = document.getElementById('add-row');
    let rowIndex = {{ count($items) }};

    function updateAmount(row) {
        const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
        const price = parseFloat(row.querySelector('.item-price').value) || 0;
        row.querySelector('.item-amount').textContent = (qty * price).toFixed(2);
    }

    tbody.querySelectorAll('tr').forEach(function(row) {
        row.querySelector('.item-qty').addEventListener('input', function() { updateAmount(row); });
        row.querySelector('.item-price').addEventListener('input', function() { updateAmount(row); });
        updateAmount(row);
        row.querySelector('.remove-row').addEventListener('click', function() {
            if (tbody.querySelectorAll('tr').length > 1) row.remove();
        });
    });

    addBtn.addEventListener('click', function() {
        const tr = document.createElement('tr');
        tr.innerHTML = '<td><input type="text" name="items[' + rowIndex + '][description]" required></td>' +
            '<td><input type="number" name="items[' + rowIndex + '][quantity]" min="1" value="1" class="item-qty" required></td>' +
            '<td><input type="number" name="items[' + rowIndex + '][unit_price]" step="0.01" min="0" value="0" class="item-price" required></td>' +
            '<td class="item-amount">0.00</td>' +
            '<td><button type="button" class="btn remove-row">Remove</button></td>';
        tbody.appendChild(tr);
        tr.querySelector('.item-qty').addEventListener('input', function() { updateAmount(tr); });
        tr.querySelector('.item-price').addEventListener('input', function() { updateAmount(tr); });
        tr.querySelector('.remove-row').addEventListener('click', function() {
            if (tbody.querySelectorAll('tr').length > 1) tr.remove();
        });
        rowIndex++;
    });
})();
</script>
