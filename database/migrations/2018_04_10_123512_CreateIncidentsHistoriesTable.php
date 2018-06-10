<?php
/**
 *
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Pascal Stiemer <pascal.stiemer@cn-consult.eu>
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncidentsHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('incidents_histories', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('incidents_id');
            $table->integer('status');
            $table->longText('message');
            $table->timestamps();
            $table->softDeletes();

            $table->index('incidents_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('incidents');
    }
}
