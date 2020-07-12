<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFK extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calendar_operations', function (Blueprint $table) {

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('fc_character_id')
                ->references('character_id')
                ->on('character_infos')
                ->onDelete('cascade');
        });

        Schema::table('calendar_attendees', function (Blueprint $table) {

            $table->foreign('character_id')
                ->references('character_id')
                ->on('character_infos')
                ->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('calendar_operations', function (Blueprint $table) {

            $table->dropForeign('calendar_operations_user_id_foreign');
            $table->dropForeign('calendar_operations_fc_character_id_foreign');
        });

        Schema::table('calendar_attendees', function (Blueprint $table) {
            $table->dropForeign('calendar_attendees_character_id_foreign');
        });
           
    }
}
