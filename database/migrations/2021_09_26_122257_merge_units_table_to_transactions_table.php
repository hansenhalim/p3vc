<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MergeUnitsTableToTransactionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('transactions', function (Blueprint $table) {
      $table->enum('cluster_per', ['sqm', 'mth'])->after('unit_id');
      $table->unsignedInteger('cluster_cost')->after('unit_id');
      $table->float('area_sqm', 6, 2)->after('unit_id');
      $table->string('cluster_name')->after('unit_id');
      $table->string('customer_name')->after('unit_id');
      $table->string('unit_name')->after('unit_id');
      $table->unsignedBigInteger('customer_id')->after('unit_id');
    });

    DB::statement('UPDATE `transactions`,`units` JOIN `customers` ON (`customers`.`id` = `units`.`customer_id`) JOIN `clusters` ON (`clusters`.`id` = `units`.`cluster_id`) SET `transactions`.`unit_name`=`units`.`name`,`transactions`.`customer_id`=`customers`.`previous_id`,`transactions`.`customer_name`=`customers`.`name`,`transactions`.`cluster_name`=`clusters`.`name`,`transactions`.`area_sqm`=`units`.`area_sqm`,`transactions`.`cluster_cost`=`clusters`.`cost`,`transactions`.`cluster_per`=`clusters`.`per` WHERE `transactions`.`unit_id`=`units`.`id`');
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('transactions', function (Blueprint $table) {
      $table->dropColumn('cluster_per');
      $table->dropColumn('cluster_cost');
      $table->dropColumn('area_sqm');
      $table->dropColumn('cluster_name');
      $table->dropColumn('customer_name');
      $table->dropColumn('unit_name');
      $table->dropColumn('customer_id');
    });
  }
}
