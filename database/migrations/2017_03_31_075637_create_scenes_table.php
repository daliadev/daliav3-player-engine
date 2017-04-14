<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScenesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
       Schema::create('scenes', function (Blueprint $table) {
         $table->increments('id');
         $table->string('name');
         $table->integer('sequence_id')->unsigned();
         $table->foreign('sequence_id')
                ->references('id')
                ->on('sequences')
                ->onDelete('cascade')
                ->onUpdate('cascade');

     });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
       Schema::table('scenes', function(Blueprint $table) {
           $table->dropForeign('scenes_sequence_id_foreign');
       });
       Schema::drop('scenes');
     }
}
