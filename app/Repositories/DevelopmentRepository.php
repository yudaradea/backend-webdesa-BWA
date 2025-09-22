<?php

namespace App\Repositories;

use App\Interfaces\DevelopmentRepositoryInterfaces;
use App\Models\Development;
use Exception;
use Illuminate\Support\Facades\DB;

class DevelopmentRepository implements DevelopmentRepositoryInterfaces
{
    public function getAll(?string $search, ?int $limit, bool $execute)
    {
        $query = Development::where(function ($query) use ($search) {
            if ($search) {
                $query->search($search);
            }
        })->with('developmentApplicants.user');

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
        $query = Development::where('id', $id)->with('developmentApplicants.user');
        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $development = new Development;
            $development->thumbnail = $data['thumbnail']->store('assets/developments', 'public');
            $development->name = $data['name'];
            $development->description = $data['description'];
            $development->person_in_charge = $data['person_in_charge'];
            $development->start_date = $data['start_date'];
            $development->end_date = $data['end_date'];
            $development->amount = $data['amount'];
            $development->status = $data['status'];
            $development->save();

            DB::commit();
            return $development;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();

        try {
            $development = Development::find($id);

            // jika ada perubahan thumbnail maka hapus file lama dan menyimpan file baru
            if (isset($data['thumbnail'])) {
                if ($development->thumbnail && file_exists(storage_path('app/public/' . $development->thumbnail))) {
                    unlink(storage_path('app/public/' . $development->thumbnail));
                }
                $development->thumbnail = $data['thumbnail']->store('assets/developments', 'public');
            }

            $development->name = $data['name'];
            $development->description = $data['description'];
            $development->person_in_charge = $data['person_in_charge'];
            $development->start_date = $data['start_date'];
            $development->end_date = $data['end_date'];
            $development->amount = $data['amount'];
            $development->status = $data['status'];
            $development->save();

            DB::commit();
            return $development;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();

        try {
            $development = Development::find($id);

            // menghapus file thumbnail jika ada
            if ($development->thumbnail && file_exists(storage_path('app/public/' . $development->thumbnail))) {
                unlink(storage_path('app/public/' . $development->thumbnail));
            }

            $development->delete();

            DB::commit();
            return $development;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
