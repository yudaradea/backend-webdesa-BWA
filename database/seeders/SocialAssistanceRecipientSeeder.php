<?php

namespace Database\Seeders;

use App\Models\HeadOfFamily;
use App\Models\SocialAssistance;
use App\Models\SocialAssistanceRecipient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialAssistanceRecipientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $socialAssistance = SocialAssistance::all();
        $headOfFamily = HeadOfFamily::all();
        foreach ($socialAssistance as $assistance) {
            // Ambil 5 head of family secara acak untuk setiap social assistance
            $randomHeads = $headOfFamily->random(5);
            foreach ($randomHeads as $head) {
                SocialAssistanceRecipient::factory()->create([
                    'social_assistance_id' => $assistance->id,
                    'head_of_family_id' => $head->id,
                ]);
            }
        }
    }
}
