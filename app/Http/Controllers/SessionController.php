<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSessionRequest;
use App\Http\Requests\UpdateSessionRequest;
use App\Models\Client;
use App\Services\SessionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SessionController extends Controller
{
    public function __construct(
        private readonly SessionService $sessionService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->get('search'),
            'client_id' => $request->get('client_id'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'sort_by' => $request->get('sort_by', 'session_date'),
            'sort_direction' => $request->get('sort_direction', 'desc'),
        ];

        $sessions = $this->sessionService->getAllSessions(15, $filters);
        $stats = $this->sessionService->getSessionStats();
        $clients = Client::orderBy('name')->get();

        return view('sessions.index', compact('sessions', 'stats', 'clients', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $clients = Client::active()->orderBy('name')->get();
        $selectedClientId = $request->get('client_id');

        return view('sessions.create', compact('clients', 'selectedClientId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSessionRequest $request): RedirectResponse
    {
        try {
            $this->sessionService->createSession($request->validated());

            return redirect()
                ->route('sessions.index')
                ->with('success', 'Session created successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create session. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $session = $this->sessionService->getSessionById($id);

        return view('sessions.show', compact('session'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $session = $this->sessionService->getSessionById($id);
        $clients = Client::active()->orderBy('name')->get();

        return view('sessions.edit', compact('session', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSessionRequest $request, int $id): RedirectResponse
    {
        try {
            $this->sessionService->updateSession($id, $request->validated());

            return redirect()
                ->route('sessions.show', $id)
                ->with('success', 'Session updated successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update session. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->sessionService->deleteSession($id);

            return redirect()
                ->route('sessions.index')
                ->with('success', 'Session deleted successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to delete session. Please try again.');
        }
    }
}
