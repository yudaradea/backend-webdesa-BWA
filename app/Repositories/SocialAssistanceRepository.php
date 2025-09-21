<?php

namespace App\Repositories;

use App\Interfaces\SocialAssistanceRepositoryInterfaces;
use App\Models\SocialAssistance;
use Exception;
use Illuminate\Support\Facades\DB;

class SocialAssistanceRepository implements SocialAssistanceRepositoryInterfaces
{
    public function getAll(?string $search, ?int $limit, bool $execute)
    {
        $query = SocialAssistance::where(function ($query) use ($search) {

            // jika ada parameter search dia akan melakukan search yang kita definisikan pada model user
            if ($search) {
                $query->search($search);
            }
        })->with('socialAssistanceRecipients');

        if ($limit) {

            // take adalah mengambil beberapa data berdasarkan limit
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
        $query = SocialAssistance::where('id', $id)->with('socialAssistanceRecipients');
        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $socialAssistance = new SocialAssistance;
            $socialAssistance->thumbnail = $data['thumbnail']->store('assets/social-assistances', 'public');
            $socialAssistance->name = $data['name'];
            $socialAssistance->category = $data['category'];
            $socialAssistance->amount = $data['amount'];
            $socialAssistance->provider = $data['provider'];
            $socialAssistance->description = $data['description'];
            $socialAssistance->is_available = $data['is_available'];
            $socialAssistance->save();

            DB::commit();

            return $socialAssistance;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();
        try {
            $socialAssistance = $this->getById($id);
            if (isset($data['thumbnail'])) {
                // menghapus file lama
                if ($socialAssistance->thumbnail && file_exists(storage_path('app/public/' . $socialAssistance->thumbnail))) {
                    unlink(storage_path('app/public/' . $socialAssistance->thumbnail));
                }
                $socialAssistance->thumbnail = $data['thumbnail']->store('assets/social-assistances', 'public');
            }
            $socialAssistance->name = $data['name'];
            $socialAssistance->category = $data['category'];
            $socialAssistance->amount = $data['amount'];
            $socialAssistance->provider = $data['provider'];
            $socialAssistance->description = $data['description'];
            $socialAssistance->is_available = $data['is_available'];
            $socialAssistance->save();

            DB::commit();

            return $socialAssistance;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();

        try {
            $socialAssistance = SocialAssistance::find($id);

            // menghapus file lama
            if ($socialAssistance->thumbnail && file_exists(storage_path('app/public/' . $socialAssistance->thumbnail))) {
                unlink(storage_path('app/public/' . $socialAssistance->thumbnail));
            }

            $socialAssistance->delete();

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
