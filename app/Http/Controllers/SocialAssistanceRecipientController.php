<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\SocialAssistanceRecipient\SocialAssistanceRecipientStoreRequest;
use App\Http\Requests\SocialAssistanceRecipient\SocialAssistanceRecipientUpdateRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\SocialAssistanceRecipientResource;
use App\Interfaces\SocialAssistanceRecipientRepositoryInterface;
use App\Models\SocialAssistanceRecipient;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class SocialAssistanceRecipientController extends Controller implements HasMiddleware
{
    private SocialAssistanceRecipientRepositoryInterface $socialAssistanceRecipientRepository;
    public function __construct(SocialAssistanceRecipientRepositoryInterface $socialAssistanceRecipientRepository)
    {
        $this->socialAssistanceRecipientRepository = $socialAssistanceRecipientRepository;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using(['social-assistance-recipient-list|social-assistance-recipient-create|social-assistance-recipient-edit|social-assistance-recipient-delete']), only: ['index', 'getAllPaginated', 'show']),

            new Middleware(PermissionMiddleware::using(['social-assistance-recipient-create']), only: ['store']),

            new Middleware(PermissionMiddleware::using(['social-assistance-recipient-edit']), only: ['update']),

            new Middleware(PermissionMiddleware::using(['social-assistance-recipient-delete']), only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(true, 'Data Penerima Bantuan Sosial Berhasil Diambil', SocialAssistanceRecipientResource::collection($socialAssistanceRecipient), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Penerima Bantuan Sosial Gagal Diambil', $e->getMessage(), 500);
        }
    }

    public function getAllPaginated(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer',
        ]);
        try {
            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page']
            );

            return ResponseHelper::jsonResponse(true, 'Data Penerima Bantuan Sosial Berhasil Diambil', PaginateResource::make($socialAssistanceRecipient, SocialAssistanceRecipientResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Penerima Bantuan Sosial Gagal Diambil', $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SocialAssistanceRecipientStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->create($request);

            return ResponseHelper::jsonResponse(true, 'Data Penerima Bantuan Sosial Berhasil Ditambahkan', new SocialAssistanceRecipientResource($socialAssistanceRecipient), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Penerima Bantuan Sosial Gagal Ditambahkan', $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->getById($id);

            if (!$socialAssistanceRecipient) {
                return ResponseHelper::jsonResponse(false, 'Data Penerima Bantuan Sosial Tidak Ditemukan', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data Penerima Bantuan Sosial Berhasil Diambil', new SocialAssistanceRecipientResource($socialAssistanceRecipient), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Penerima Bantuan Sosial Gagal Diambil', $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SocialAssistanceRecipientUpdateRequest $request, string $id)
    {
        $request = $request->validated();

        try {
            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->getById($id);

            if (!$socialAssistanceRecipient) {
                return ResponseHelper::jsonResponse(false, 'Data Penerima Bantuan Sosial Tidak Ditemukan', null, 404);
            }

            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->update($id, $request);

            return ResponseHelper::jsonResponse(true, 'Data Penerima Bantuan Sosial Berhasil Diupdate', new SocialAssistanceRecipientResource($socialAssistanceRecipient), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Penerima Bantuan Sosial Gagal Diupdate', $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->getById($id);
            if (!$socialAssistanceRecipient) {
                return ResponseHelper::jsonResponse(false, 'Data Penerima Bantuan Sosial Tidak Ditemukan', null, 404);
            }

            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->delete($id);

            return ResponseHelper::jsonResponse(true, 'Data Penerima Bantuan Sosial Berhasil Dihapus', null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Penerima Bantuan Sosial Gagal Dihapus', $e->getMessage(), 500);
        }
    }
}
