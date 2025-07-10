<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');           // nama barang
        $table->integer('stock');         // stok barang
        $table->integer('price');         // harga satuan
        $table->foreignId('category_id')  // relasi ke kategori
              ->constrained('categories')
              ->onDelete('cascade');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
