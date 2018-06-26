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

class CreateDowntimeTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('downtimes', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->timestamps();
            $table->dateTime('resolved_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('downtimes');
    }
}
