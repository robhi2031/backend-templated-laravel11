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
        Schema::create('profil_instansi', function (Blueprint $table) {
            $table->id();
            $table->string('name', 225);
            $table->string('short_description', 225);
            $table->text('logo')->nullable();
            $table->string('phone_number', 25)->nullable();
            $table->string('email')->nullable();
            $table->string('office_address')->nullable();
            $table->text('office_address_coordinate')->nullable();
            $table->integer('user_updated')->nullable();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // Trigger
        DB::statement('
            CREATE TRIGGER upd_timestamp_trigger
            BEFORE UPDATE
            ON profil_instansi
            FOR EACH ROW
            EXECUTE PROCEDURE upd_timestamp();
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP TRIGGER IF EXISTS upd_timestamp_trigger ON profil_instansi');
        Schema::dropIfExists('profil_instansi');
    }
};
