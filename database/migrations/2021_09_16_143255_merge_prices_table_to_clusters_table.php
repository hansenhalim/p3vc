<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MergePricesTableToClustersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    //create new fields
    Schema::table('clusters', function (Blueprint $table) {
      $table->enum('per', ['sqm', 'mth'])->after('name');
      $table->unsignedInteger('cost')->after('name');
    });

    //move content to newly created fields
    DB::statement('UPDATE `clusters`,`prices` SET `clusters`.`cost`=`prices`.`cost`, `clusters`.`per`=`prices`.`per` WHERE `prices`.`cluster_id`=`clusters`.`id`');

    //drop old table
    Schema::dropIfExists('prices');
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    //create new table
    Schema::create('prices', function (Blueprint $table) {
      $table->foreignId('cluster_id');
      $table->unsignedInteger('cost');
      $table->enum('per', ['sqm', 'mth']);
      $table->datetime('approved_at')->nullable();
      $table->foreignId('approved_by')->nullable();
      $table->timestamps();
      $table->foreignId('updated_by')->nullable();
      $table->softDeletes();
    });

    //move content to newly created table
    DB::statement('INSERT INTO `prices` (`cost`,`per`,`cluster_id`) SELECT `cost`,`per`,`id` FROM `clusters`');

    //drop old fields
    Schema::table('clusters', function (Blueprint $table) {
      $table->dropColumn('cost');
      $table->dropColumn('per');
    });
  }
}
