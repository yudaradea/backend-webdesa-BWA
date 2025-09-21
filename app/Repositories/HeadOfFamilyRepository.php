<?php

namespace App\Repositories;

use App\Interfaces\HeadOfFamilyRepositoryInterface;
use App\Models\HeadOfFamily;
use Exception;
use Illuminate\Support\Facades\DB;

class HeadOfFamilyRepository implements HeadOfFamilyRepositoryInterface
{
    public function getAll(?string $search, ?int $limit, bool $execute)
    {
        $query = HeadOfFamily::where(function ($query) use ($search) {

            // jika ada parameter search dia akan melakukan search yang kita definisikan pada model user
            if ($search) {
                $query->search($search);
            }
        })->with(['familyMembers', 'socialAssistanceRecipients']);

        $query->orderBy('created_at', 'DESC');

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
        $query = HeadOfFamily::where('id', $id)->with('familyMembers', 'socialAssistances', 'socialAssistanceRecipients');
        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            // karena head of family itu memiliki relasi one to one dengan user maka kita bisa langsung memanggil user repository, dan membuat user
            $userRepository = new UserRepository();
            $user = $userRepository->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],

            ]);

            $headOfFamily = new HeadOfFamily;
            $headOfFamily->user_id = $user->id;
            $headOfFamily->profile_picture = $data['profile_picture']->store('assets/head-of-families', 'public');
            $headOfFamily->identity_number = $data['identity_number'];
            $headOfFamily->gender = $data['gender'];
            $headOfFamily->date_of_birth = $data['date_of_birth'];
            $headOfFamily->phone_number = $data['phone_number'];
            $headOfFamily->occupation = $data['occupation'];
            $headOfFamily->marital_status = $data['marital_status'];
            $headOfFamily->save();

            DB::commit();
            return $headOfFamily;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();

        try {
            $headOfFamily = HeadOfFamily::find($id);

            if (isset($data['profile_picture'])) {
                // menghapus file lama
                if ($headOfFamily->profile_picture && file_exists(storage_path('app/public/' . $headOfFamily->profile_picture))) {
                    unlink(storage_path('app/public/' . $headOfFamily->profile_picture));
                }
                $headOfFamily->profile_picture = $data['profile_picture']->store('assets/head-of-families', 'public');
            }

            $headOfFamily->identity_number = $data['identity_number'];
            $headOfFamily->gender = $data['gender'];
            $headOfFamily->date_of_birth = $data['date_of_birth'];
            $headOfFamily->phone_number = $data['phone_number'];
            $headOfFamily->occupation = $data['occupation'];
            $headOfFamily->marital_status = $data['marital_status'];
            $headOfFamily->save();

            // update user
            $userRepository = new UserRepository();
            $userRepository->update($headOfFamily->user_id, [
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

            DB::commit();
            return $headOfFamily;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();

        try {
            $headOfFamily = HeadOfFamily::find($id);

            // menghapus foto profile
            if ($headOfFamily->profile_picture && file_exists(storage_path('app/public/' . $headOfFamily->profile_picture))) {
                unlink(storage_path('app/public/' . $headOfFamily->profile_picture));
            }

            // menghapus user
            $headOfFamily->delete();
            $userRepository = new UserRepository();
            $userRepository->delete($headOfFamily->user_id);



            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
