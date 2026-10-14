<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private array $columns = [
        'profile_photo_path' => 'string',
        'profile_picture' => 'string',
        'phone' => 'string',
        'location' => 'string',
        'bio' => 'text',
    ];

    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach ($this->columns as $name => $type) {
                if (! Schema::hasColumn('users', $name)) {
                    $table->{$type}($name)->nullable();
                }
            }
        });
    }

    public function down(): void
    {
        $existing = array_filter(
            array_keys($this->columns),
            fn ($name) => Schema::hasColumn('users', $name)
        );

        if ($existing) {
            Schema::table('users', fn (Blueprint $table) => $table->dropColumn($existing));
        }
    }
};
