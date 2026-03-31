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
        Schema::table('bookings', function (Blueprint $table) {
            // Adding columns to store the status of each action
            $table->boolean('send_payment_link')->default(0); // status of sending payment link
            $table->boolean('send_booking_detail')->default(0); // status of sending booking detail
            $table->boolean('send_login_detail')->default(0); // status of sending login detail
            $table->boolean('send_document_verified')->default(0); // status of sending document verified mail
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Dropping the columns if the migration is rolled back
            $table->dropColumn('send_payment_link');
            $table->dropColumn('send_booking_detail');
            $table->dropColumn('send_login_detail');
            $table->dropColumn('send_document_verified');
        });
    }
};
