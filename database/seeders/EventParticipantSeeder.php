<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\HeadOfFamily;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $event = Event::all();
        $headOfFamily = HeadOfFamily::all();
        foreach ($event as $evt) {
            // Ambil 5 head of family secara acak untuk setiap event
            $randomHeads = $headOfFamily->random(5);
            foreach ($randomHeads as $head) {
                EventParticipant::factory()->create([
                    'event_id' => $evt->id,
                    'head_of_family_id' => $head->id,
                ]);
            }
        }
    }
}
