<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            // Parent Menu (nullable = menu utama)
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('menus')
                ->nullOnDelete();
            // Route yang dipilih
            $table->foreignId('system_route_id')
                ->nullable()
                ->constrained('system_routes')
                ->nullOnDelete();
            // Nama menu
            $table->string('title');
            // Icon
            $table->string('icon')->nullable();
            // Urutan menu
            $table->unsignedInteger('sort_order')->default(0);
            // Tampil di Sidebar?
            $table->boolean('is_sidebar')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
