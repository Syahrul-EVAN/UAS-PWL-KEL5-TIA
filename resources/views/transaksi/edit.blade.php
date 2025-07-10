<!DOCTYPE html>
<html>
<head>
    <title>Edit Transaksi</title>
</head>
<body>
    <h2>Edit Transaksi</h2>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('transaksi.update', $transaksi->id) }}">
        @csrf
        @method('PUT')

        <label>Nama Barang:</label>
        <select name="product_id" required>
            @foreach($products as $p)
                <option value="{{ $p->id }}" {{ $transaksi->product_id == $p->id ? 'selected' : '' }}>
                    {{ $p->name }}
                </option>
            @endforeach
        </select><br><br>

        <label>Jumlah:</label>
        <input type="number" name="quantity" value="{{ $transaksi->quantity }}" required><br><br>

        <button type="submit">UPDATE</button>
    </form>

    <a href="{{ route('transaksi.index') }}">← Kembali</a>
</body>
</html>
