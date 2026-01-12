<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'total_billable_hours' => $this->when($this->relationLoaded('clientSessions'), $this->total_billable_hours),
            'total_revenue' => $this->when($this->relationLoaded('invoices'), $this->total_revenue),
            'sessions_count' => $this->when($this->relationLoaded('clientSessions'), $this->clientSessions->count()),
            'invoices_count' => $this->when($this->relationLoaded('invoices'), $this->invoices->count()),
            'sessions' => ClientSessionResource::collection($this->whenLoaded('clientSessions')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'deleted_at' => $this->deleted_at?->toISOString(),
        ];
    }
}
