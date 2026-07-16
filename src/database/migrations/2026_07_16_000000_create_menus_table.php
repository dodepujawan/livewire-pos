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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            
            $table->string('route_name')->unique();
            $table->string('permission_name');
            $table->string('display_name');
            $table->string('group')->nullable();
            $table->string('icon')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_metadata_manual')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('show_in_sidebar')->default(true);
            $table->string('parent_route_name')->nullable();
            
            $table->timestamps();
            
            $table->index('route_name');
            $table->index('permission_name');
            $table->index('group');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
