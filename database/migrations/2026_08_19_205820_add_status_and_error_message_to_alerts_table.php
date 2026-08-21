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
        Schema::table('alerts', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('user_id'); 
            $table->text('error_messages')->nullable()->after('status');
            $table->timestamp('sent_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alerts', function (Blueprint $table) {
            Schema::table('alerts', function (Blueprint $table){
                $table->dropColumn(['status', 'error_messages']);
            });
        });
    }
};
