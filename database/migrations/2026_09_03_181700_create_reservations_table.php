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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id')->constrained()->cascadeOnDelete();
            $table->string('client_reference', 50);
            $table->string('customer_name', 50);
            $table->string('customer_email', 50);
            $table->unsignedTinyInteger('is_active')->default(1);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE reservations ADD UNIQUE INDEX idempotent (offer_id, (IF(is_active=0, updated_at, 1)))');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
