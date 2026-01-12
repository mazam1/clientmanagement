<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use App\Services\ClientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ClientController extends Controller
{
    public function __construct(
        private readonly ClientService $clientService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = [
            'search' => $request->get('search'),
            'status' => $request->get('status'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_direction' => $request->get('sort_direction', 'desc'),
        ];

        $perPage = $request->get('per_page', 15);
        $clients = $this->clientService->getAllClients($perPage, $filters);

        return ClientResource::collection($clients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request): JsonResponse
    {
        $client = $this->clientService->createClient($request->validated());

        return (new ClientResource($client))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): ClientResource
    {
        $client = $this->clientService->getClientById($id);

        return new ClientResource($client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, int $id): ClientResource
    {
        $client = $this->clientService->updateClient($id, $request->validated());

        return new ClientResource($client);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->clientService->deleteClient($id);

        return response()->json([
            'message' => 'Client deleted successfully',
        ], 200);
    }

    /**
     * Get client statistics.
     */
    public function stats(): JsonResponse
    {
        $stats = $this->clientService->getClientStats();

        return response()->json($stats);
    }
}
