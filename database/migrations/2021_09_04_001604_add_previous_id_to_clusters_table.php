<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddPreviousIdToClustersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('clusters', function (Blueprint $table) {
      $table->unsignedBigInteger('previous_id')->after('id')->nullable();
    });

    //hydrate newly created field
    DB::statement('UPDATE `clusters` SET `previous_id`=`id`');
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('clusters', function (Blueprint $table) {
      $table->dropColumn('previous_id');
    });
  }
}
