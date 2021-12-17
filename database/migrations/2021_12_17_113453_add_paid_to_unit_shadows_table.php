<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaidToUnitShadowsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('unit_shadows', function (Blueprint $table) {
      $table->unsignedInteger('paid_months_count');
      $table->unsignedInteger('paid_months_total');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('unit_shadows', function (Blueprint $table) {
      $table->dropColumn('paid_months_count');
      $table->dropColumn('paid_months_total');
    });
  }
}
