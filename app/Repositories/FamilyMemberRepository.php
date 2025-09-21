<?php

namespace App\Repositories;

use App\Interfaces\FamilyMemberRepositoryInterface;
use App\Models\FamilyMember;
use Illuminate\Support\Facades\DB;

class FamilyMemberRepository implements FamilyMemberRepositoryInterface
{
    public function getAll(?string $search, ?int $limit, bool $execute)
    {
        $query = FamilyMember::where(function ($query) use ($search) {

            // jika ada parameter search dia akan melakukan search yang kita definisikan pada model user
            if ($search) {
                $query->search($search);
            }
        })->with('headOfFamily');

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
        $query = FamilyMember::where('id', $id)->with('headOfFamily');
        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            // karena family member itu memiliki relasi one to one dengan user maka kita bisa langsung memanggil user repository, dan membuat user
            $userRepository = new UserRepository;
            $user = $userRepository->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            $familyMember = new FamilyMember;
            $familyMember->user_id = $user->id;
            $familyMember->head_of_family_id = $data['head_of_family_id'];
            $familyMember->profile_picture = $data['profile_picture']->store('assets/family-members', 'public');
            $familyMember->identity_number = $data['identity_number'];
            $familyMember->gender = $data['gender'];
            $familyMember->date_of_birth = $data['date_of_birth'];
            $familyMember->phone_number = $data['phone_number'];
            $familyMember->occupation = $data['occupation'];
            $familyMember->marital_status = $data['marital_status'];
            $familyMember->relation = $data['relation'];
            $familyMember->save();

            DB::commit();
            return $familyMember;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();

        try {
            $familyMember = FamilyMember::find($id);

            if (isset($data['profile_picture'])) {
                // jika ada gambar lama maka dihapus
                if ($familyMember->profile_picture && file_exists(storage_path('app/public/' . $familyMember->profile_picture))) {
                    unlink(storage_path('app/public/' . $familyMember->profile_picture));
                }
                $familyMember->profile_picture = $data['profile_picture']->store('assets/family-members', 'public');
            }
            $familyMember->identity_number = $data['identity_number'];
            $familyMember->gender = $data['gender'];
            $familyMember->date_of_birth = $data['date_of_birth'];
            $familyMember->phone_number = $data['phone_number'];
            $familyMember->occupation = $data['occupation'];
            $familyMember->marital_status = $data['marital_status'];
            $familyMember->relation = $data['relation'];
            $familyMember->save();

            // update user
            $userRepository = new UserRepository();
            $userRepository->update($familyMember->user_id, [
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

            DB::commit();
            return $familyMember;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();

        try {
            $familyMember = FamilyMember::find($id);

            // jika ada gambar lama maka dihapus
            if ($familyMember->profile_picture && file_exists(storage_path('app/public/' . $familyMember->profile_picture))) {
                unlink(storage_path('app/public/' . $familyMember->profile_picture));
            }

            $familyMember->delete();

            $userRepository = new UserRepository();
            $userRepository->delete($familyMember->user_id);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}
