<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_routes', function (Blueprint $table) {
            $table->id();
            // Nama route Laravel
            $table->string('route_name')->unique();
            // URI route
            $table->string('uri');
            // GET|POST|PUT|DELETE
            $table->string('method');
            // Controller / Livewire
            $table->string('action')->nullable();
            // Waktu terakhir disinkronkan
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_routes');
    }
};
