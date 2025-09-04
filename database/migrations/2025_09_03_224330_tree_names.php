<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up() {
Schema::create('tree_names', function (Blueprint $table) {
$table->id();
$table->string('common_name');
$table->string('scientific_name')->nullable();
$table->string('family')->nullable();
$table->timestamps();
});
}
public function down() { Schema::dropIfExists('tree_names'); }
};