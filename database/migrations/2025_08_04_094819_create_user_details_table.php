<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enum\DocumentStatus;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id', 'user_details_user_fk');
            $table->string('passport')->nullable();
            $table->string('passport_number',50);
            $table->string('international_lic')->nullable();
            $table->string('international_lic_back')->nullable();

            $table->string('idp_number',50);
            $table->string('regular_lic')->nullable();
            $table->string('regular_lic_back')->nullable();
            $table->string('regular_lic_number',50);
            $table->string('status')->default(DocumentStatus::PENDING->value);
            $table->string('regular_lic_status')->default(DocumentStatus::PENDING->value);
            $table->string('international_lic_status')->default(DocumentStatus::PENDING->value);
            $table->string('	regular_lic_back_status')->default(DocumentStatus::PENDING->value);
            $table->string('international_lic_back_status')->default(DocumentStatus::PENDING->value);
            $table->string('experience')->nullable();
            $table->integer('bike_ridden')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
