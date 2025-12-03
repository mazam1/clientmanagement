<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Services\ClientService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function __construct(
        private readonly ClientService $clientService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->get('search'),
            'status' => $request->get('status'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_direction' => $request->get('sort_direction', 'desc'),
        ];

        $clients = $this->clientService->getAllClients(15, $filters);
        $stats = $this->clientService->getClientStats();

        return view('clients.index', compact('clients', 'stats', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request): RedirectResponse
    {
        try {
            $this->clientService->createClient($request->validated());

            return redirect()
                ->route('clients.index')
                ->with('success', 'Client created successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create client. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $client = $this->clientService->getClientById($id);

        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $client = $this->clientService->getClientById($id);

        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, int $id): RedirectResponse
    {
        try {
            $this->clientService->updateClient($id, $request->validated());

            return redirect()
                ->route('clients.show', $id)
                ->with('success', 'Client updated successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update client. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->clientService->deleteClient($id);

            return redirect()
                ->route('clients.index')
                ->with('success', 'Client deleted successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to delete client. Please try again.');
        }
    }

    /**
     * Export clients to CSV.
     */
    public function export(Request $request): Response
    {
        $filters = [
            'search' => $request->get('search'),
            'status' => $request->get('status'),
        ];

        $csv = $this->clientService->exportToCSV($filters);
        $filename = 'clients-'.date('Y-m-d-His').'.csv';

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
