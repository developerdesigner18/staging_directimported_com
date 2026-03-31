<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enum\BookingStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('booking_id')->unique();
            $table->foreignId('bike_id')->constrained('bikes');
            $table->string('email');
            $table->string('first_name');
            $table->string('last_name');
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location')->nullable();
            $table->boolean('policy_status');
            $table->longText('comment')->nullable();
            $table->string('status')->default(BookingStatus::PROCESSING->value);
            $table->json('selected_accessories')->nullable();
            $table->longText('included_accessories')->nullable();
            $table->longText('email_comment')->nullable();
            $table->longText('system_comment')->nullable();
            $table->longText('final_comment')->nullable();
            $table->boolean('payment_link_status')->default(false);
            $table->boolean('booking_detail_sent_status')->default(false);
            $table->decimal('price',11)->default('0.00');
            $table->tinyInteger('insurance')->default('0');
            $table->decimal('insurance_price',11)->default('0.00');
            $table->longText('table_data')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
