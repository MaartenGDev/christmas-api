<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReservedUserIdToGifts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gifts', function (Blueprint $table) {
            $table->integer('reserved_by')->unsigned()->nullable();
            $table->foreign('reserved_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gifts', function (Blueprint $table) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            $table->dropColumn('reserved_by');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        });
    }
}
