<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('external_id', 20)->unique();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->date('check_in')->index();
            $table->date('check_out')->index();
            $table->unsignedInteger('max_guests');
            $table->decimal('price', 10, 2)->index();
            $table->string('currency', 3)->default('EUR')->index();
            $table->unsignedInteger('available_units');
            $table->datetime('expires_at')->nullable();
            $table->unique(['supplier_id', 'external_id']);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
