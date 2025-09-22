<?php

namespace App\Repositories;

use App\Interfaces\EventRepositoryInterfaces;
use App\Models\Event;
use Exception;
use Illuminate\Support\Facades\DB;

class EventRepository implements EventRepositoryInterfaces
{
    public function getAll(?string $search, ?int $limit, bool $execute)
    {
        $query = Event::where(function ($query) use ($search) {
            if ($search) {
                $query->search($search);
            }
        })->with('eventParticipants.headOfFamily');

        if ($limit) {
            $query->take($limit);
        }

        if ($execute) {
            return $query->get();
        }

        return $query;
    }

    public function getAllPaginated(?string $search, ?int $rowPerPage)
    {
        $query = $this->getAll($search, $rowPerPage, false);

        return $query->paginate($rowPerPage);
    }

    public function getById(string $id)
    {
        $query = Event::where('id', $id)->with('eventParticipants.headOfFamily');
        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $event = new Event;
            $event->thumbnail = $data['thumbnail']->store('assets/events', 'public');
            $event->name = $data['name'];
            $event->description = $data['description'];
            $event->price = $data['price'];
            $event->date = $data['date'];
            $event->time = $data['time'];
            $event->is_active = $data['is_active'];
            $event->save();

            DB::commit();

            return $event;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();

        try {
            $event = Event::find($id);

            // jika ada perubahan thumbnail maka hapus file lama dan menyimpan file baru
            if (isset($data['thumbnail'])) {
                if ($event->thumbnail && file_exists(storage_path('app/public/' . $event->thumbnail))) {
                    unlink(storage_path('app/public/' . $event->thumbnail));
                }
                $event->thumbnail = $data['thumbnail']->store('assets/events', 'public');
            }

            $event->name = $data['name'];
            $event->description = $data['description'];
            $event->price = $data['price'];
            $event->date = $data['date'];
            $event->time = $data['time'];
            $event->is_active = $data['is_active'];
            $event->save();

            DB::commit();

            return $event;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();

        try {
            $event = Event::find($id);

            // menghapus file thumbnail jika ada
            if ($event->thumbnail && file_exists(storage_path('app/public/' . $event->thumbnail))) {
                unlink(storage_path('app/public/' . $event->thumbnail));
            }

            $event->delete();

            DB::commit();
            return $event;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
