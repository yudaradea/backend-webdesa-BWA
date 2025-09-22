<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\DevelopmentApplicant\DevelopmentApplicantStoreRequest;
use App\Http\Requests\DevelopmentApplicant\DevelopmentApplicantUpdateRequest;
use App\Http\Resources\DevelopmentApplicantResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\DevelopmentApplicantRepositoryInterfaces;
use Illuminate\Http\Request;

class DevelopmentApplicantController extends Controller
{
    private DevelopmentApplicantRepositoryInterfaces $developmentApplicantRepository;

    public function __construct(DevelopmentApplicantRepositoryInterfaces $developmentApplicantRepository)
    {
        $this->developmentApplicantRepository = $developmentApplicantRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $developmentApplicants = $this->developmentApplicantRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(true, 'Data Development Applicant Berhasil Diambil', DevelopmentApplicantResource::collection($developmentApplicants), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Development Applicant Gagal Diambil', $e->getMessage(), 500);
        }
    }

    public function getAllPaginated(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer',
        ]);
        try {
            $developmentApplicants = $this->developmentApplicantRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page']
            );

            return ResponseHelper::jsonResponse(true, 'Data Development Applicant Berhasil Diambil', PaginateResource::make($developmentApplicants, DevelopmentApplicantResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Development Applicant Gagal Diambil', $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DevelopmentApplicantStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $developmentApplicant = $this->developmentApplicantRepository->create($request);

            return ResponseHelper::jsonResponse(true, 'Data Development Applicant Berhasil Ditambahkan', new DevelopmentApplicantResource($developmentApplicant), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Development Applicant Gagal Ditambahkan', $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $developmentApplicant = $this->developmentApplicantRepository->getById($id);

            if (!$developmentApplicant) {
                return ResponseHelper::jsonResponse(false, 'Data Development Applicant Tidak Ditemukan', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data Development Applicant Berhasil Diambil', new DevelopmentApplicantResource($developmentApplicant), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Development Applicant Gagal Diambil', $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DevelopmentApplicantUpdateRequest $request, string $id)
    {
        $request = $request->validated();

        try {
            $developmentApplicant = $this->developmentApplicantRepository->getById($id);

            if (!$developmentApplicant) {
                return ResponseHelper::jsonResponse(false, 'Data Development Applicant Tidak Ditemukan', null, 404);
            }
            $developmentApplicant = $this->developmentApplicantRepository->update($id, $request);

            return ResponseHelper::jsonResponse(true, 'Data Development Applicant Berhasil Diupdate', new DevelopmentApplicantResource($developmentApplicant), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Development Applicant Gagal Diupdate', $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $developmentApplicant = $this->developmentApplicantRepository->getById($id);

            if (!$developmentApplicant) {
                return ResponseHelper::jsonResponse(false, 'Data Development Applicant Tidak Ditemukan', null, 404);
            }

            $developmentApplicant = $this->developmentApplicantRepository->delete($id);

            return ResponseHelper::jsonResponse(true, 'Data Development Applicant Berhasil Dihapus', null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Development Applicant Gagal Dihapus', $e->getMessage(), 500);
        }
    }
}
