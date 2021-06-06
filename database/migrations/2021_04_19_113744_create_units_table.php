<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('units', function (Blueprint $table) {
      $table->id();
      $table->foreignId('customer_id')->constrained();
      $table->foreignId('cluster_id')->constrained();
      $table->string('name');
      $table->float('area_sqm', 6, 2);
      $table->string('idlink')->nullable();
      $table->datetime('approved_at')->nullable();
      $table->foreignId('approved_by')->nullable()->constrained('users');
      $table->timestamps();
      $table->foreignId('updated_by')->nullable()->constrained('users');
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('units');
  }
}
