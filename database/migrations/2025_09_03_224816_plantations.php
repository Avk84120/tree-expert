<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up() {
Schema::create('plantations', function (Blueprint $table) {
$table->id();
$table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
$table->string('name')->nullable();
$table->json('area_coordinates')->nullable(); // Geo coords
$table->integer('total_trees')->nullable();
$table->timestamps();
});
}
public function down() { Schema::dropIfExists('plantations'); }
};