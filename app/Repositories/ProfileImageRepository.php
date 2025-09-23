<?php

namespace App\Repositories;

use App\Interfaces\ProfileImageRepositoryInterfaces;
use App\Models\ProfileImage;
use Exception;
use Illuminate\Support\Facades\DB;

class ProfileImageRepository implements ProfileImageRepositoryInterfaces
{
    public function getAll(?int $limit, bool $execute)
    {
        $query = ProfileImage::query()->orderBy('created_at', 'DESC');

        if ($limit) {
            $query->take($limit);
        }

        if ($execute) {
            return $query->get();
        }

        return $query;
    }

    public function getAllPaginated(?int $rowPerPage)
    {
        $query = $this->getAll($rowPerPage, false);
        return $query->paginate($rowPerPage);
    }

    public function getById(string $id)
    {
        $query = ProfileImage::where('id', $id);
        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $profileImage = new ProfileImage;
            $profileImage->profile_id = $data['profile_id'];
            $profileImage->image = $data['image']->store('assets/profiles', 'public');
            $profileImage->save();

            DB::commit();
            return $profileImage;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();

        try {
            $profileImage = ProfileImage::find($id);
            $profileImage->profile_id = $data['profile_id'];

            // jika ada perubahan thumbnail maka hapus file lama dan menyimpan file baru
            if (isset($data['image'])) {
                if ($profileImage->image && file_exists(storage_path('app/public/' . $profileImage->image))) {
                    unlink(storage_path('app/public/' . $profileImage->image));
                }
                $profileImage->image = $data['image']->store('assets/profiles', 'public');
            }
            $profileImage->save();

            DB::commit();
            return $profileImage;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();

        try {
            $profileImage = ProfileImage::find($id);
            // menghapus file image jika ada
            if ($profileImage->image && file_exists(storage_path('app/public/' . $profileImage->image))) {
                unlink(storage_path('app/public/' . $profileImage->image));
            }
            $profileImage->delete();
            DB::commit();
            return $profileImage;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
