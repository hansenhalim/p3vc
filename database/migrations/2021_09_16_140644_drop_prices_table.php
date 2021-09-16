<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropPricesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::dropIfExists('prices');
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::create('prices', function (Blueprint $table) {
      $table->foreignId('cluster_id')->constrained();
      $table->unsignedInteger('cost');
      $table->enum('per', ['sqm', 'mth']);
      $table->datetime('approved_at')->nullable();
      $table->foreignId('approved_by')->nullable()->constrained('users');
      $table->timestamps();
      $table->foreignId('updated_by')->nullable()->constrained('users');
      $table->softDeletes();
    });
  }
}
