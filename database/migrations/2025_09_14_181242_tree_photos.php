<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tree_photos', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tree_id')->nullable();
    $table->string('path');        // file path
    $table->decimal('latitude', 10, 7)->nullable();
    $table->decimal('longitude', 10, 7)->nullable();
    $table->string('accuracy')->nullable();
    $table->timestamps();

    $table->foreign('tree_id')->references('id')->on('trees')->onDelete('set null');
});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
