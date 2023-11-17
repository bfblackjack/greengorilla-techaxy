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
        Schema::create('publishers', function(Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('buyers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('contacts', function(Blueprint $table) {
            $table->id();
            $table->uuid('internal_id');
            $table->string('external_id')->nullable();
            $table->string('caller_id');
            $table->json('additional_attributes')->nullable();
            $table->enum('status', ['billed', 'nonbillable', 'unknown']);
            $table->timestamps();
        });
        Schema::create('settings_log_actions', function(Blueprint $table) {
            $table->id();
            $table->string('action');
        });
        Schema::create('contact_logs', function(Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->references('id')->on('contacts');
            $table->foreignId('action_id')->references('id')->on('settings_log_actions');
            $table->foreignId('author_id')->references('id')->on('users');
            $table->timestamps();
        });
        Schema::create('buyer_contacts', function(Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->references('id')->on('buyers');
            $table->foreignId('contact_id')->references('id')->on('contacts');
            $table->timestamps();
        });
        Schema::create('publisher_contacts', function(Blueprint $table) {
            $table->id();
            $table->foreignId('publisher_id')->references('id')->on('publishers');
            $table->foreignId('contact_id')->references('id')->on('contacts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publisher_contacts');
        Schema::dropIfExists('buyer_contacts');
        Schema::dropIfExists('contact_logs');
        Schema::dropIfExists('settings_log_actions');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('buyers');
        Schema::dropIfExists('publishers');
    }
};
