<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientSession;
use App\Models\Invoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('query', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $results = [];

        try {
            // Search clients
            $clients = Client::where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('phone', 'like', "%{$query}%");
            })
                ->limit(5)
                ->get();

            foreach ($clients as $client) {
                $results[] = [
                    'type' => 'client',
                    'title' => $client->name,
                    'subtitle' => $client->email ?? 'No email',
                    'url' => route('clients.show', $client),
                ];
            }

            // Search sessions
            $sessions = ClientSession::with('client')
                ->where(function ($q) use ($query) {
                    $q->whereHas('client', function ($subQ) use ($query) {
                        $subQ->where('name', 'like', "%{$query}%");
                    })
                        ->orWhere('session_type', 'like', "%{$query}%")
                        ->orWhere('notes', 'like', "%{$query}%");
                })
                ->limit(5)
                ->get();

            foreach ($sessions as $session) {
                if ($session->client) {
                    $results[] = [
                        'type' => 'session',
                        'title' => "{$session->client->name} - {$session->session_type}",
                        'subtitle' => $session->session_date->format('M d, Y'),
                        'url' => route('sessions.show', $session),
                    ];
                }
            }

            // Search invoices
            $invoices = Invoice::with('client')
                ->where(function ($q) use ($query) {
                    $q->where('invoice_number', 'like', "%{$query}%")
                        ->orWhereHas('client', function ($subQ) use ($query) {
                            $subQ->where('name', 'like', "%{$query}%");
                        });
                })
                ->limit(5)
                ->get();

            foreach ($invoices as $invoice) {
                if ($invoice->client) {
                    $results[] = [
                        'type' => 'invoice',
                        'title' => $invoice->invoice_number,
                        'subtitle' => "{$invoice->client->name} - $".number_format($invoice->total_amount, 2),
                        'url' => route('invoices.show', $invoice),
                    ];
                }
            }

            return response()->json(array_slice($results, 0, 10));
        } catch (\Exception $e) {
            \Log::error('Search error: '.$e->getMessage());

            return response()->json([]);
        }
    }
}
