<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCostAndPerToClustersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('clusters', function (Blueprint $table) {
      $table->unsignedInteger('cost');
      $table->enum('per', ['sqm', 'mth']);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('clusters', function (Blueprint $table) {
      $table->dropColumn('cost');
      $table->dropColumn('per');
    });
  }
}
