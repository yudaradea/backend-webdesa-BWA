<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Profile\ProfileStoreRequest;
use App\Http\Requests\Profile\ProfileUpdateRequest;
use App\Http\Resources\ProfileResource;
use App\Interfaces\ProfileRepositoryInterfaces;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private ProfileRepositoryInterfaces $profileRepository;

    public function __construct(ProfileRepositoryInterfaces $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $profile = $this->profileRepository->getProfile();
            return ResponseHelper::jsonResponse(true, 'Data Profile Berhasil Diambil', ProfileResource::make($profile), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProfileStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $profile = $this->profileRepository->create($request);

            return ResponseHelper::jsonResponse(true, 'Data Profile Berhasil Ditambahkan', ProfileResource::make($profile), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $profile = $this->profileRepository->getById($id);

            if (!$profile) {
                return ResponseHelper::jsonResponse(false, 'Data Profile Tidak Ditemukan', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data Profile Berhasil Diambil', ProfileResource::make($profile), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileUpdateRequest $request, string $id)
    {
        $request = $request->validated();

        try {
            $profile = $this->profileRepository->getById($id);

            if (!$profile) {
                return ResponseHelper::jsonResponse(false, 'Data Profile Tidak Ditemukan', null, 404);
            }

            $profile = $this->profileRepository->update($id, $request);

            return ResponseHelper::jsonResponse(true, 'Data Profile Berhasil Diupdate', ProfileResource::make($profile), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $profile = $this->profileRepository->getById($id);

            if (!$profile) {
                return ResponseHelper::jsonResponse(false, 'Data Profile Tidak Ditemukan', null, 404);
            }

            $profile = $this->profileRepository->delete($id);

            return ResponseHelper::jsonResponse(true, 'Data Profile Berhasil Dihapus', ProfileResource::make($profile), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
