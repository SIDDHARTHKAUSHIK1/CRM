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
        Schema::create('whatsapp_campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('brochure_path');
            $table->string('brochure_name')->nullable();
            $table->text('caption')->nullable();
            $table->string('status')->default('draft'); // draft, running, paused, completed, cancelled
            $table->unsignedInteger('throttle_seconds')->default(20);
            $table->unsignedInteger('daily_limit')->nullable();
            $table->unsignedInteger('consecutive_failure_limit')->default(5);
            $table->unsignedInteger('consecutive_failures')->default(0);
            $table->text('pause_reason')->nullable();
            $table->unsignedInteger('total_recipients')->default(0);
            $table->unsignedInteger('sent_count')->default(0);
            $table->unsignedInteger('failed_count')->default(0);
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_campaigns');
    }
};
