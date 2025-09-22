<?php

namespace App\Repositories;

use App\Interfaces\DevelopmentApplicantRepositoryInterfaces;
use App\Models\DevelopmentApplicant;
use Exception;
use Illuminate\Support\Facades\DB;

class DevelopmentApplicantRepository implements DevelopmentApplicantRepositoryInterfaces
{
    public function getAll(
        ?string $search,
        ?int $limit,
        bool $execute
    ) {
        $query = DevelopmentApplicant::where(function ($query) use ($search) {
            if ($search) {
                $query->search($search);
            }
        })->with(['development', 'user']);

        if ($limit) {
            $query->take($limit);
        }

        if ($execute) {
            return $query->get();
        }
        return $query;
    }

    public function getAllPaginated(
        ?string $search,
        ?int $rowPerPage
    ) {
        $query = $this->getAll(
            $search,
            $rowPerPage,
            false
        );

        return $query->paginate($rowPerPage);
    }

    public function getById(string $id)
    {
        $query = DevelopmentApplicant::where('id', $id)->with(['development', 'user']);
        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $developmentApplicant = new DevelopmentApplicant;
            $developmentApplicant->development_id = $data['development_id'];
            $developmentApplicant->user_id = $data['user_id'];
            $developmentApplicant->save();

            DB::commit();
            return $developmentApplicant;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();

        try {
            $developmentApplicant = DevelopmentApplicant::find($id);
            $developmentApplicant->development_id = $data['development_id'];
            $developmentApplicant->user_id = $data['user_id'];
            $developmentApplicant->status = $data['status'];
            $developmentApplicant->save();

            DB::commit();
            return $developmentApplicant;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();

        try {
            $developmentApplicant = DevelopmentApplicant::find($id);
            $developmentApplicant->delete();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
