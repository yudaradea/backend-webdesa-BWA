<?php

namespace App\Repositories;

use App\Interfaces\ProfileRepositoryInterfaces;
use App\Models\Profile;
use Exception;
use Illuminate\Support\Facades\DB;

class ProfileRepository implements ProfileRepositoryInterfaces
{
    public function getProfile()
    {
        return Profile::with('profileImages')->orderBy('created_at', 'desc')->first();
    }

    public function getById(string $id)
    {
        $query = Profile::where('id', $id)->with('profileImages');
        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $profile = new Profile;
            $profile->thumbnail = $data['thumbnail']->store('assets/profiles', 'public');
            $profile->name = $data['name'];
            $profile->about = $data['about'];
            $profile->headman = $data['headman'];
            $profile->people = $data['people'];
            $profile->agricultural_area = $data['agricultural_area'];
            $profile->total_area = $data['total_area'];
            $profile->save();

            DB::commit();
            return $profile;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();

        try {
            $profile = Profile::find($id);

            // jika ada perubahan thumbnail maka hapus file lama dan menyimpan file baru
            if (isset($data['thumbnail'])) {
                if ($profile->thumbnail && file_exists(storage_path('app/public/' . $profile->thumbnail))) {
                    unlink(storage_path('app/public/' . $profile->thumbnail));
                }
                $profile->thumbnail = $data['thumbnail']->store('assets/profiles', 'public');
            }

            $profile->name = $data['name'];
            $profile->about = $data['about'];
            $profile->headman = $data['headman'];
            $profile->people = $data['people'];
            $profile->agricultural_area = $data['agricultural_area'];
            $profile->total_area = $data['total_area'];
            $profile->save();

            DB::commit();
            return $profile;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();

        try {
            $profile = Profile::find($id);

            // menghapus file thumbnail jika ada
            if ($profile->thumbnail && file_exists(storage_path('app/public/' . $profile->thumbnail))) {
                unlink(storage_path('app/public/' . $profile->thumbnail));
            }

            $profile->delete();

            DB::commit();
            return $profile;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
