<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitShadowsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('unit_shadows', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('customer_id');
      $table->string('name');
      $table->string('customer_name');
      $table->float('area_sqm', 6, 2);
      $table->string('idlink')->nullable();
      $table->integer('balance');
      $table->integer('debt');
      $table->unsignedInteger('months_count');
      $table->unsignedInteger('months_total');
      $table->unsignedInteger('credit');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('unit_shadows');
  }
}
