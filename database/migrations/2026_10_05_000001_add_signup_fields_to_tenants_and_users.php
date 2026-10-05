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
        // 1. Update tenants table
        if (Schema::hasTable('tenants')) {
            Schema::table('tenants', function (Blueprint $table) {
                if (! Schema::hasColumn('tenants', 'slug')) {
                    $table->string('slug', 120)->nullable()->unique()->after('name');
                }

                if (! Schema::hasColumn('tenants', 'owner_user_id')) {
                    $table->unsignedInteger('owner_user_id')->nullable()->after('status');
                    $table->index('owner_user_id');
                }

                if (! Schema::hasColumn('tenants', 'contact_email')) {
                    $table->string('contact_email', 191)->nullable()->after('owner_user_id');
                }

                if (! Schema::hasColumn('tenants', 'contact_phone')) {
                    $table->string('contact_phone', 20)->nullable()->after('contact_email');
                }

                if (! Schema::hasColumn('tenants', 'signup_source')) {
                    $table->string('signup_source', 30)->default('admin')->after('contact_phone');
                }

                if (! Schema::hasColumn('tenants', 'signup_ip')) {
                    $table->string('signup_ip', 45)->nullable()->after('signup_source');
                }

                if (! Schema::hasColumn('tenants', 'trial_ends_at')) {
                    $table->timestamp('trial_ends_at')->nullable()->after('signup_ip');
                }
            });

            // Backfill Tenant 1 slug to 'default' if null
            DB::table('tenants')
                ->where('id', 1)
                ->whereNull('slug')
                ->update(['slug' => 'default']);
        }

        // 2. Update users table
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (! Schema::hasColumn('users', 'phone')) {
                    $table->string('phone', 20)->nullable()->after('email');
                }

                if (! Schema::hasColumn('users', 'email_verified_at')) {
                    $table->timestamp('email_verified_at')->nullable()->after('phone');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('tenants')) {
            Schema::table('tenants', function (Blueprint $table) {
                $dropCols = [];

                if (Schema::hasColumn('tenants', 'slug')) {
                    $table->dropUnique(['slug']);
                    $dropCols[] = 'slug';
                }

                if (Schema::hasColumn('tenants', 'owner_user_id')) {
                    $table->dropIndex(['owner_user_id']);
                    $dropCols[] = 'owner_user_id';
                }

                foreach (['contact_email', 'contact_phone', 'signup_source', 'signup_ip', 'trial_ends_at'] as $col) {
                    if (Schema::hasColumn('tenants', $col)) {
                        $dropCols[] = $col;
                    }
                }

                if (! empty($dropCols)) {
                    $table->dropColumn($dropCols);
                }
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $dropCols = [];
                if (Schema::hasColumn('users', 'phone')) {
                    $dropCols[] = 'phone';
                }
                if (Schema::hasColumn('users', 'email_verified_at')) {
                    $dropCols[] = 'email_verified_at';
                }
                if (! empty($dropCols)) {
                    $table->dropColumn($dropCols);
                }
            });
        }
    }
};
