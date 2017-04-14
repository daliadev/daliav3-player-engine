<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
        Schema::create('results', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('user_id')->unsigned();
          $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
          $table->integer('activite_id')->unsigned();
          $table->foreign('activite_id')
                 ->references('id')
                 ->on('activites')
                 ->onDelete('cascade')
                 ->onUpdate('cascade');
          $table->integer('scene_count');
          $table->integer('curent_scene');

       });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
       Schema::table('results', function(Blueprint $table) {
          $table->dropForeign('results_user_id_foreign');
          $table->dropForeign('results_activite_id_foreign');
       });
       Schema::drop('results');
     }
}
