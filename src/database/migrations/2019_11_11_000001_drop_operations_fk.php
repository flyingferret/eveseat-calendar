<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropOperationsFK extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // We need to to drop the foreign key constraints prior to the migration to v4.
        // If they dont exist they will error so just skip all exceptions

        // Get current foreign keys in the DB

        // $foreignKeys = DB::select("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE CONSTRAINT_NAME <> 'PRIMARY'");
        

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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
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
            $table->foreign('tag_id')
                  ->references('id')
                  ->on('calendar_tags')
                  ->onDelete('cascade');

            $table->foreign('operation_id')
                  ->references('id')
                  ->on('calendar_operations')
                  ->onDelete('cascade');
        });


    }


    public function listTableForeignKeys($table)
    {
        $conn = Schema::getConnection()->getDoctrineSchemaManager();

        return array_map(function($key) {
            return $key->getName();
        }, $conn->listTableForeignKeys($table));
    }
}
