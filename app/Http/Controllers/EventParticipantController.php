<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\EventParticipant\EventParticipantStoreRequest;
use App\Http\Requests\EventParticipant\EventParticipantUpdateRequest;
use App\Http\Resources\EventParticipantResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\EventParticipantRepositoryInterfaces;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class EventParticipantController extends Controller implements HasMiddleware
{

    private EventParticipantRepositoryInterfaces $eventParticipantRepository;

    public function __construct(EventParticipantRepositoryInterfaces $eventParticipantRepository)
    {
        $this->eventParticipantRepository = $eventParticipantRepository;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using(['event-participant-list|event-participant-create|event-participant-edit|event-participant-delete']), only: ['index', 'getAllPaginated', 'show']),

            new Middleware(PermissionMiddleware::using(['event-participant-create']), only: ['store']),

            new Middleware(PermissionMiddleware::using(['event-participant-edit']), only: ['update']),

            new Middleware(PermissionMiddleware::using(['event-participant-delete']), only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $eventParticipants = $this->eventParticipantRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(true, 'Data Event Participant Berhasil Diambil', EventParticipantResource::collection($eventParticipants), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Event Participant Gagal Diambil', $e->getMessage(), 500);
        }
    }

    public function getAllPaginated(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer',
        ]);
        try {
            $eventParticipants = $this->eventParticipantRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page']
            );

            return ResponseHelper::jsonResponse(true, 'Data Event Participant Berhasil Diambil', PaginateResource::make($eventParticipants, EventParticipantResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Event Participant Gagal Diambil', $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventParticipantStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $eventParticipant = $this->eventParticipantRepository->create($request);

            return ResponseHelper::jsonResponse(true, 'Data Event Participant Berhasil Ditambahkan', new EventParticipantResource($eventParticipant), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Event Participant Gagal Ditambahkan', $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $eventParticipant = $this->eventParticipantRepository->getById($id);

            if (!$eventParticipant) {
                return ResponseHelper::jsonResponse(false, 'Data Event Participant Tidak Ditemukan', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data Event Participant Berhasil Diambil', new EventParticipantResource($eventParticipant), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Event Participant Gagal Diambil', $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventParticipantUpdateRequest $request, string $id)
    {
        $request = $request->validated();

        try {
            $eventParticipant = $this->eventParticipantRepository->getById($id);

            if (!$eventParticipant) {
                return ResponseHelper::jsonResponse(false, 'Data Event Participant Tidak Ditemukan', null, 404);
            }
            $eventParticipant = $this->eventParticipantRepository->update($id, $request);
            return ResponseHelper::jsonResponse(true, 'Data Event Participant Berhasil Diupdate', new EventParticipantResource($eventParticipant), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Event Participant Gagal Diupdate', $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $eventParticipant = $this->eventParticipantRepository->getById($id);
            if (!$eventParticipant) {
                return ResponseHelper::jsonResponse(false, 'Data Event Participant Tidak Ditemukan', null, 404);
            }

            $eventParticipant = $this->eventParticipantRepository->delete($id);

            return ResponseHelper::jsonResponse(true, 'Data Event Participant Berhasil Dihapus', null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Event Participant Gagal Dihapus', $e->getMessage(), 500);
        }
    }
}
