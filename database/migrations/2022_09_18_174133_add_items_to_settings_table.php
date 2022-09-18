<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::table('settings')->updateOrInsert(
            ['key' => 'furnace.heating_mode'],
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'furnace.central_heating.temperature.min'],
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'furnace.central_heating.temperature.max'],
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'furnace.water_heating.temperature.min'],
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'furnace.water_heating.temperature.max'],
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::table('settings')
            ->where('key', 'furnace.heating_mode')
            ->delete();

        DB::table('settings')
            ->where('key', 'furnace.central_heating.temperature.min')
            ->delete();

        DB::table('settings')
            ->where('key', 'furnace.central_heating.temperature.max')
            ->delete();

        DB::table('settings')
            ->where('key', 'furnace.water_heating.temperature.min')
            ->delete();

        DB::table('settings')
            ->where('key', 'furnace.water_heating.temperature.max')
            ->delete();
    }
};
