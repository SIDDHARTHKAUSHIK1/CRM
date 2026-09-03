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
        Schema::create('whatsapp_campaign_recipients', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('whatsapp_campaign_id');
            $table->string('raw_input')->nullable();
            $table->string('phone_e164', 30);
            $table->string('status')->default('pending'); // pending, sending, sent, failed, skipped_dnc, skipped_invalid, skipped
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->unsignedInteger('attempts')->default(0);
            $table->timestamps();

            $table->foreign('whatsapp_campaign_id')
                ->references('id')
                ->on('whatsapp_campaigns')
                ->onDelete('cascade');

            $table->unique(['whatsapp_campaign_id', 'phone_e164'], 'uniq_camp_recipient');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_campaign_recipients');
    }
};
