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
        Schema::create('locales', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->string('name');
            $table->string('direction', 8)->default('ltr');
            $table->boolean('store_enabled')->default(true);
            $table->boolean('admin_enabled')->default(true);
            $table->string('logo_path')->nullable();
            $table->timestamps();
        });

        $this->seedDefaultLocalesFromConfig();
    }

    /**
     * Seed rows from config when migrating existing installs.
     */
    protected function seedDefaultLocalesFromConfig(): void
    {
        $available = config('app.available_locales', ['en' => 'English']);
        $now = now();

        foreach ($available as $code => $name) {
            DB::table('locales')->updateOrInsert(
                ['code' => $code],
                [
                    'name' => $name,
                    'direction' => $this->guessDirection($code),
                    'store_enabled' => true,
                    'admin_enabled' => false,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }

    protected function guessDirection(string $code): string
    {
        $rtl = ['ar', 'fa', 'he', 'ur', 'ku', 'dv'];

        foreach ($rtl as $prefix) {
            if (str_starts_with(strtolower($code), $prefix)) {
                return 'rtl';
            }
        }

        return 'ltr';
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locales');
    }
};
