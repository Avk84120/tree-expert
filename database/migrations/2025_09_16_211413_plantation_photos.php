<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plantation_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plantation_id')
                  ->constrained('plantations')
                  ->onDelete('cascade');
            $table->string('path'); // file path in storage
            $table->string('caption')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plantation_photos');
    }
};
