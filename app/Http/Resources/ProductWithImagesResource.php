<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ImageResource;
use App\Http\Resources\ReviewResource;
use App\Models\Category;

class ProductWithImagesResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'amount' => $this->amount,
            'content' => $this->content,
            'preview'=> $this->preview,
            'price' => $this->price,
            'category' => new CategoryResource($this->category),
            'images' => ImageResource::collection($this->images),
            'reviews' => ReviewResource::collection($this->reviews()->latest()->take(15)->get()),
            'average_rating' => $this->reviews()->avg('rating'),
            'total_reviews' => $this->reviews()->count(),
        ];
    }
}
