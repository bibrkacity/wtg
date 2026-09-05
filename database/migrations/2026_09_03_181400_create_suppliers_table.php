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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique();
            $table->string('email', 100)->unique();
            $table->string('phone', 20)->unique();
            $table->string('telegram', 20)->unique();
            $table->string('whatsapp', 50)->nullable();
            $table->text('payment_details', 11);
            $table->string('status', 15)->default('active')->index();
            $table->text('comment')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
