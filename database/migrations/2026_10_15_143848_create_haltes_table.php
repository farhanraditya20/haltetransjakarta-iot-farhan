<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('haltes', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->integer('kapasitas')->default(200);
        $table->integer('jumlah_sekarang')->default(0);
        $table->double('lat');
        $table->double('lng');
        $table->json('koridor')->nullable();
        $table->timestamps();
    });
}

    public function down()
    {
        Schema::dropIfExists('haltes');
    }
};