<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;

class TransactionController extends Controller
{
    // Tampilkan halaman utama
    public function index()
    {
        $products = Product::with('category')->get();
        $transactions = Transaction::with('product')->latest()->get();
        return view('transaksi.index', compact('products', 'transactions'));
    }

    // Simpan transaksi baru
    public function store(Request $request)
    {
        $product = Product::find($request->product_id);

        if (!$product || $product->stock < $request->quantity) {
            return back()->with('error', 'Stok tidak mencukupi atau produk tidak ditemukan.');
        }

        $total = $product->price * $request->quantity;

        Transaction::create([
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'total_price' => $total,
        ]);

        $product->stock -= $request->quantity;
        $product->save();

        return back()->with('success', 'Transaksi berhasil ditambahkan.');
    }

    // Update transaksi (edit)
    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $product = $transaction->product;

        // Kembalikan stok sebelumnya
        $product->stock += $transaction->quantity;

        // Validasi stok
        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Stok tidak mencukupi untuk update.');
        }

        // Hitung total harga baru
        $total = $product->price * $request->quantity;

        // Update transaksi
        $transaction->update([
            'quantity' => $request->quantity,
            'total_price' => $total,
        ]);

        // Kurangi stok lagi sesuai quantity baru
        $product->stock -= $request->quantity;
        $product->save();

        return back()->with('success', 'Transaksi berhasil diupdate.');
    }

    // Hapus transaksi
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $product = $transaction->product;

        // Kembalikan stok saat transaksi dihapus
        $product->stock += $transaction->quantity;
        $product->save();

        $transaction->delete();
        return back()->with('success', 'Transaksi berhasil dihapus.');
    }
}
