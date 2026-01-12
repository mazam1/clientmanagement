<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSessionRequest;
use App\Http\Requests\UpdateSessionRequest;
use App\Http\Resources\ClientSessionResource;
use App\Services\SessionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SessionController extends Controller
{
    public function __construct(
        private readonly SessionService $sessionService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = [
            'search' => $request->get('search'),
            'client_id' => $request->get('client_id'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'sort_by' => $request->get('sort_by', 'session_date'),
            'sort_direction' => $request->get('sort_direction', 'desc'),
        ];

        $perPage = $request->get('per_page', 15);
        $sessions = $this->sessionService->getAllSessions($perPage, $filters);

        return ClientSessionResource::collection($sessions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSessionRequest $request): JsonResponse
    {
        $session = $this->sessionService->createSession($request->validated());

        return (new ClientSessionResource($session))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): ClientSessionResource
    {
        $session = $this->sessionService->getSessionById($id);

        return new ClientSessionResource($session);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSessionRequest $request, int $id): ClientSessionResource
    {
        $session = $this->sessionService->updateSession($id, $request->validated());

        return new ClientSessionResource($session);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->sessionService->deleteSession($id);

        return response()->json([
            'message' => 'Session deleted successfully',
        ], 200);
    }

    /**
     * Get session statistics.
     */
    public function stats(): JsonResponse
    {
        $stats = $this->sessionService->getSessionStats();

        return response()->json($stats);
    }

    /**
     * Get upcoming sessions.
     */
    public function upcoming(Request $request): AnonymousResourceCollection
    {
        $days = $request->get('days', 7);
        $sessions = $this->sessionService->getUpcomingSessions($days);

        return ClientSessionResource::collection($sessions);
    }
}
