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
    Schema::create('trees', function (Blueprint $table) {
    $table->id();
    $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
    $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
    $table->foreignId('tree_name_id')->nullable()->constrained('tree_names')->onDelete('set null');
    // $table->foreignId('project_id')->constrained()->onDelete('cascade');
    $table->string('ward')->nullable();
    $table->string('tree_no')->nullable();
    $table->string('tree_name');
    $table->string('scientific_name')->nullable();
    $table->string('family')->nullable();
    $table->decimal('girth_cm', 8, 2)->nullable();
    $table->decimal('height_m', 8, 2)->nullable();
    $table->decimal('canopy_m', 8, 2)->nullable();
    $table->integer('age')->nullable();
    $table->enum('condition', ['Poor','Medium','Good','Diseased','Dead'])->nullable();
    $table->string('address')->nullable();
    $table->string('landmark')->nullable();
    $table->enum('ownership', ['Private','Government','Park','Road','Open Space','Riverside'])->nullable();    
    $table->string('concern_person_name')->nullable();
    $table->text('remark')->nullable();
    $table->decimal('latitude', 10, 7)->nullable();
    $table->decimal('longitude', 10, 7)->nullable();
    $table->string('accuracy')->nullable();
    $table->string('continue')->nullable();
    $table->string('photo')->nullable(); // path to image
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trees');
    }
};
