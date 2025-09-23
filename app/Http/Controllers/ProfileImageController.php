<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\ProfileImage\ProfileImageStoreRequest;
use App\Http\Requests\ProfileImage\ProfileImageUpdateRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\ProfileImageResource;
use App\Interfaces\ProfileImageRepositoryInterfaces;
use Illuminate\Http\Request;

class ProfileImageController extends Controller
{
    private ProfileImageRepositoryInterfaces $profileImageRepository;

    public function __construct(ProfileImageRepositoryInterfaces $profileImageRepository)
    {
        $this->profileImageRepository = $profileImageRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $profileImage = $this->profileImageRepository->getAll(
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(true, 'Data Profile Image Berhasil Diambil', ProfileImageResource::collection($profileImage), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function getAllPaginated(Request $request)
    {
        $request = $request->validate([
            'row_per_page' => 'required|integer'
        ]);

        try {
            $profileImage = $this->profileImageRepository->getAllPaginated(
                $request['row_per_page']
            );

            return ResponseHelper::jsonResponse(true, 'Data Profile Image Berhasil Diambil', PaginateResource::make($profileImage, ProfileImageResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProfileImageStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $profileImage = $this->profileImageRepository->create($request);

            return ResponseHelper::jsonResponse(true, 'Data Profile Image Berhasil Ditambahkan', ProfileImageResource::make($profileImage), 201);
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
            $profileImage = $this->profileImageRepository->getById($id);

            if (!$profileImage) {
                return ResponseHelper::jsonResponse(false, 'Data Profile Image Tidak Ditemukan', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data Profile Image Berhasil Diambil', ProfileImageResource::make($profileImage), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileImageUpdateRequest $request, string $id)
    {
        $request = $request->validated();

        try {
            $profileImage = $this->profileImageRepository->getById($id);

            if (!$profileImage) {
                return ResponseHelper::jsonResponse(false, 'Data Profile Image Tidak Ditemukan', null, 404);
            }

            $profileImage = $this->profileImageRepository->update($id, $request);

            return ResponseHelper::jsonResponse(true, 'Data Profile Image Berhasil Diupdate', ProfileImageResource::make($profileImage), 200);
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
            $profileImage = $this->profileImageRepository->getById($id);

            if (!$profileImage) {
                return ResponseHelper::jsonResponse(false, 'Data Profile Image Tidak Ditemukan', null, 404);
            }

            $profileImage = $this->profileImageRepository->delete($id);

            return ResponseHelper::jsonResponse(true, 'Data Profile Image Berhasil Dihapus', ProfileImageResource::make($profileImage), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
