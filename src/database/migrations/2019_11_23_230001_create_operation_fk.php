<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationFK extends Migration
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
            $table->foreign('operation_id')
                ->references('id')
                ->on('calendar_operations')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('character_id')
                ->references('character_id')
                ->on('character_infos')
                ->onDelete('cascade');
        });

        Schema::table('calendar_tag_operation', function (Blueprint $table) {

            $table->foreign('operation_id')
                  ->references('id')
                  ->on('calendar_operations')
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
            $table->dropForeign('calendar_attendees_operation_id_foreign');
            $table->dropForeign('calendar_attendees_character_id_foreign');
            $table->dropForeign('calendar_attendees_user_id_foreign');
        });
        

        Schema::table('calendar_tag_operation', function (Blueprint $table) {
            $table->dropForeign('calendar_tag_operation_operation_id_foreign');
        });
           
    }
}
