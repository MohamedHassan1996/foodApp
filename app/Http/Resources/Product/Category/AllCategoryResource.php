<?php

namespace App\Http\Resources\Product\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AllCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {


        //dd($userData);

        return [
            'categoryId' => $this->id,
            'name' => $this->name,
            'path' => $this->path?Storage::disk('public')->url($this->path):"",
            'status' => $this->status
        ];
    }
}
