<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up() {
Schema::create('photos', function (Blueprint $table) {
$table->id();
$table->morphs('imageable'); // polymorphic: trees, plantations, users (aadhaar)
$table->string('path');
$table->string('type')->nullable();
$table->timestamps();
});
}
public function down() { Schema::dropIfExists('photos'); }
};