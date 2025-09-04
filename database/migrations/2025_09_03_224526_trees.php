<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up() {
Schema::create('trees', function (Blueprint $table) {
$table->id();
$table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
$table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
$table->foreignId('tree_name_id')->nullable()->constrained('tree_names')->onDelete('set null');


$table->string('tree_no')->nullable();
$table->string('common_name')->nullable();
$table->string('scientific_name')->nullable();
$table->string('family')->nullable();
$table->float('girth_cm')->nullable();
$table->float('height_m')->nullable();
$table->float('canopy_m')->nullable();
$table->integer('age')->nullable();
$table->enum('condition', ['poor','medium','good','disease','dead'])->nullable();
$table->string('ownership')->nullable();
$table->string('concern_person')->nullable();
$table->text('remark')->nullable();
$table->decimal('latitude', 10, 7)->nullable();
$table->decimal('longitude', 10, 7)->nullable();
$table->float('accuracy')->nullable();
$table->string('address')->nullable();
$table->string('landmark')->nullable();
$table->timestamps();
});
}
public function down() { Schema::dropIfExists('trees'); }
};