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
        Schema::create('site_info', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name');
            $table->string('description');
            $table->text('keyword');
            $table->text('login_bg');
            $table->text('login_logo');
            $table->text('headbackend_logo');
            $table->text('headbackend_logo_dark');
            $table->text('headbackend_icon');
            $table->text('headbackend_icon_dark');
            $table->text('copyright');
            $table->integer('user_updated')->nullable();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // Trigger
        DB::statement('
            CREATE TRIGGER upd_timestamp_trigger
            BEFORE UPDATE
            ON site_info
            FOR EACH ROW
            EXECUTE PROCEDURE upd_timestamp();
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP TRIGGER IF EXISTS upd_timestamp_trigger ON site_info');

        Schema::dropIfExists('site_info');
    }
};
