<?php

namespace Database\Seeders;

use App\Models\Development;
use App\Models\DevelopmentApplicant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DevelopmentApplicantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $developments = Development::all();

        // Loop untuk setiap development
        foreach ($developments as $dev) {
            // Ambil 5 user secara acak LANGSUNG dari database
            // Ini sangat efisien!
            $randomUsers = User::inRandomOrder()->limit(2)->get();

            // Loop untuk 5 user yang sudah dipilih secara acak
            foreach ($randomUsers as $user) {
                DevelopmentApplicant::factory()->create([
                    'development_id' => $dev->id,
                    'user_id'        => $user->id,
                ]);
            }
        }
    }
}
