<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'preferences')) {
                $table->text('preferences')->nullable();
            }

            if (! Schema::hasColumn('users', 'preferences_selected')) {
                $table->boolean('preferences_selected')->default(false);
            }
        });
    }

    public function down(): void
    {
        $existing = array_filter(
            ['preferences', 'preferences_selected'],
            fn ($name) => Schema::hasColumn('users', $name)
        );

        if ($existing) {
            Schema::table('users', fn (Blueprint $table) => $table->dropColumn($existing));
        }
    }
};
