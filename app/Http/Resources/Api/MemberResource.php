<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role?->value,
            'department' => $this->department,
            'permissions' => $this->getAllPermissions()->pluck('name'),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
