<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewCalendarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First up to move is the calendar_operations table
        // Duplicate the table into a backup for safekeeping
        DB::statement('CREATE TABLE calendar_operations_three LIKE calendar_operations');
        DB::statement('INSERT calendar_operations_three SELECT * FROM calendar_operations');

        $entries = DB::table('calendar_operations')->get();

        foreach ($entries as $entry) {
            $new_id = DB::table('mig_groups')
                ->where('old_user_id', $entry->user_id)
                ->first();
            
            if (is_null($new_id->new_user_id)) {
                // Cant find the new user id so remove the entry
                DB::table('calendar_operations')
                    ->where('id', $entry->id)
                    ->delete();
                continue;
            }

            DB::table('calendar_operations')
                ->where('user_id', $entry->user_id)
                ->update(['user_id' => $new_id->new_user_id]);
        }

        Schema::table('calendar_operations', function(Blueprint $table){

            $table->unsignedInteger('user_id')->change();
        });
        
        // Now do the same as above for the attendee table
        // Duplicate the table into a backup for safekeeping
        DB::statement('CREATE TABLE calendar_attendees_three LIKE calendar_attendees');
        DB::statement('INSERT calendar_attendees_three SELECT * FROM calendar_attendees');

        $entries = DB::table('calendar_attendees')->get();

        foreach ($entries as $entry) {
            $new_id = DB::table('mig_groups')
                ->where('old_user_id', $entry->user_id)
                ->first();
            
            if (is_null($new_id->new_user_id)) {
                // Cant find the new user id so remove the entry
                DB::table('calendar_attendees')
                    ->where('id', $entry->id)
                    ->delete();
                continue;
            }

            DB::table('calendar_attendees')
                ->where('user_id', $entry->user_id)
                ->update(['user_id' => $new_id->new_user_id]);
        }

        DB::statement('ALTER TABLE calendar_attendees MODIFY user_id int(10) unsigned NOT NULL');
        

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('calendar_operations');
        Schema::rename('calendar_operations_three', 'calendar_operations');


    }
}
