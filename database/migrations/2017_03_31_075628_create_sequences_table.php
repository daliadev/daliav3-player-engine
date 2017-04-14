<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSequencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('sequences', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name');
        $table->integer('activite_id')->unsigned();
        $table->foreign('activite_id')
               ->references('id')
               ->on('activites')
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
      Schema::table('sequences', function(Blueprint $table) {
          $table->dropForeign('sequences_activite_id_foreign');
      });
      Schema::drop('sequences');
    }
}
