<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // doctrine/dbal isn't installed in this project, so Schema::table()->change()
        // isn't available — use a raw statement instead. This project runs MySQL
        // (confirmed via DB_CONNECTION=mysql in .env).
        DB::statement('ALTER TABLE whatsapp_campaigns MODIFY brochure_path VARCHAR(255) NULL');
    }

    public function down(): void
    {
        // Not safely reversible once any row has brochure_path = NULL; left as a no-op.
    }
};
