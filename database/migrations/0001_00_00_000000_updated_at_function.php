<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fungsi Update with Timestamp
        DB::statement('CREATE OR REPLACE FUNCTION upd_timestamp() RETURNS TRIGGER
            LANGUAGE plpgsql AS $$
            BEGIN
                NEW.updated_at = CURRENT_TIMESTAMP;
                RETURN NEW;
            END;
        $$;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop function
        DB::statement('DROP FUNCTION IF EXISTS upd_timestamp()');
    }
};
