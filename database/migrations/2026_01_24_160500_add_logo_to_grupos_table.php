<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('grupos', function (Blueprint $table) {
            if (! Schema::hasColumn('grupos', 'logo_path')) {
                $table->string('logo_path', 255)->nullable()->after('codigo_externo');
            }
        });
    }

    public function down(): void
    {
        Schema::table('grupos', function (Blueprint $table) {
            if (Schema::hasColumn('grupos', 'logo_path')) {
                $table->dropColumn('logo_path');
            }
        });
    }
};
