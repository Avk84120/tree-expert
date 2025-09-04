<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up() {
Schema::create('projects', function (Blueprint $table) {
$table->id();
$table->string('name');
$table->string('state')->nullable();
$table->string('client')->nullable();
$table->string('company')->nullable();
$table->string('field_officer')->nullable();
$table->date('start_date')->nullable();
$table->date('end_date')->nullable();
$table->integer('total_wards')->nullable();
$table->json('settings')->nullable();
$table->timestamps();
});
}


public function down()
 { Schema::dropIfExists('projects'); }
};