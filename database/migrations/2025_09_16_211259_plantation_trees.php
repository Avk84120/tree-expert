<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plantation_trees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plantation_id')
                  ->constrained('plantations')
                  ->onDelete('cascade');
            $table->foreignId('tree_id')
                  ->constrained('tree_names')
                  ->onDelete('cascade');
            $table->integer('count')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plantation_trees');
    }
};
