<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permission_has_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon', 50)->nullable();
            $table->string('has_route', 10)->default('N');
            $table->string('route_name')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('permission_has_menus')->onDelete('cascade');
            $table->string('has_child', 10)->default('N');
            $table->string('is_crud', 10)->default('N');
            $table->string('order_line', 10)->nullable();
            $table->integer('user_add');
            $table->integer('user_updated')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // Trigger
        DB::statement('
            CREATE TRIGGER upd_timestamp_trigger
            BEFORE UPDATE
            ON permission_has_menus
            FOR EACH ROW
            EXECUTE PROCEDURE upd_timestamp();
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP TRIGGER IF EXISTS upd_timestamp_trigger ON permission_has_menus');
        Schema::dropIfExists('permission_has_menus');
    }
};
