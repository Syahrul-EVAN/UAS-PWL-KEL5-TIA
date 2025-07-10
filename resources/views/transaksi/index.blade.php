<!DOCTYPE html>
<html>
<head>
    <title>Kasir Kelompok 5</title>

    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 30px;
            background-color: #f8f9fa;
            font-size: 18px;
        }

        h2, h3 {
            text-align: center;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        th, td {
            padding: 14px;
            border: 1px solid #dee2e6;
        }

        th {
            background-color: #f1f1f1;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        .action-buttons {
            text-align: center;
            margin-top: 15px;
        }

        .btn {
            padding: 12px 24px;
            margin: 6px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-green {
            background-color: #2ecc71;
            color: white;
        }

        .btn-red {
            background-color: #e74c3c;
            color: white;
        }

        .btn-grey {
            background-color: #bdc3c7;
            color: white;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        select, input[type="number"], input[type="text"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .form-inline {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .alert {
            padding: 15px;
            background-color: #d1ecf1;
            color: #0c5460;
            border-left: 5px solid #17a2b8;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border-left-color: #e3342f;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>KASIR KELOMPOK 5</h2>
    <h3>Data Transaksi</h3>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    {{-- TABEL --}}
    <table>
        <thead>
        <tr>
            <th style="width: 60px;">ID</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Total Harga</th>
        </tr>
        </thead>
        <tbody>
        @foreach($transactions as $t)
            <tr>
                <td>
                    <input type="radio" name="selected" value="{{ $t->id }}"
                           data-product="{{ $t->product_id }}"
                           data-quantity="{{ $t->quantity }}">
                    {{ $t->id }}
                </td>
                <td>{{ $t->product->name }}</td>
                <td>{{ $t->quantity }}</td>
                <td>Rp {{ number_format($t->total_price, 0, ',', '.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{-- FORM --}}
    <form id="transaksiForm" method="POST" action="{{ route('transaksi.store') }}">
        @csrf
        <input type="hidden" name="_method" id="formMethod" value="POST">

        <div class="form-group">
            <label>Nama Barang:</label>
            <select id="product" name="product_id" required>
                <option value="">-- Pilih Barang --</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}"
                            data-harga="{{ $p->price }}"
                            data-stok="{{ $p->stock }}"
                            data-kategori="{{ $p->category->name }}">
                        {{ $p->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Jumlah:</label>
            <input type="number" name="quantity" required>
        </div>

        <div class="form-group">
            <label>Harga:</label>
            <input type="text" id="harga" disabled>
        </div>

        <div class="form-group">
            <label>Stock:</label>
            <input type="text" id="stok" disabled>
        </div>

        <div class="form-group">
            <label>Nama Kategori:</label>
            <input type="text" id="kategori" disabled>
        </div>

        <div class="action-buttons">
            <button type="submit" class="btn btn-green" id="submitBtn">Insert</button>
            <button type="button" class="btn btn-red" onclick="resetForm()">Cancel</button>
        </div>
    </form>

    {{-- ACTION BUTTONS --}}
    <div class="action-buttons">
        <button class="btn btn-grey" onclick="loadSelected()">Load</button>
        <button class="btn btn-grey" onclick="updateSelected()">Update</button>
        <button class="btn btn-red" onclick="deleteSelected()">Delete</button>
    </div>
</div>

{{-- SCRIPT --}}
<script>
    const form = document.getElementById('transaksiForm');
    const formMethod = document.getElementById('formMethod');
    const submitBtn = document.getElementById('submitBtn');
    const productSelect = document.getElementById('product');

    productSelect.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        document.getElementById('harga').value = selected.dataset.harga || '';
        document.getElementById('stok').value = selected.dataset.stok || '';
        document.getElementById('kategori').value = selected.dataset.kategori || '';
    });

    function resetForm() {
        form.action = '{{ route('transaksi.store') }}';
        formMethod.value = 'POST';
        submitBtn.innerText = 'Insert';
        form.reset();
        document.getElementById('harga').value = '';
        document.getElementById('stok').value = '';
        document.getElementById('kategori').value = '';
    }

    function loadToForm(id, productId, quantity) {
        form.action = '/transaksi/' + id;
        formMethod.value = 'PUT';
        submitBtn.innerText = 'Update';
        productSelect.value = productId;
        document.querySelector('input[name="quantity"]').value = quantity;
        productSelect.dispatchEvent(new Event('change'));
    }

    function getSelectedData() {
        const selected = document.querySelector('input[name="selected"]:checked');
        if (!selected) return null;

        return {
            id: selected.value,
            productId: selected.dataset.product,
            quantity: selected.dataset.quantity
        };
    }

    function loadSelected() {
        const data = getSelectedData();
        if (!data) return alert('Pilih salah satu transaksi!');
        loadToForm(data.id, data.productId, data.quantity);
    }

    function updateSelected() {
        loadSelected();
    }

    function deleteSelected() {
        const data = getSelectedData();
        if (!data) return alert('Pilih salah satu transaksi!');
        if (!confirm('Yakin ingin menghapus transaksi ini?')) return;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/transaksi/' + data.id;

        const csrf = document.createElement('input');
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';

        const method = document.createElement('input');
        method.name = '_method';
        method.value = 'DELETE';

        form.appendChild(csrf);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();

        // Optional: reload page after delete
        setTimeout(() => window.location.reload(), 1000);
    }
</script>
</body>
</html>
