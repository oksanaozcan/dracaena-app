<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ReviewResource;
use App\Models\Category;

class ProductWithReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $customerReview = $this->reviews->first();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'amount' => $this->amount,
            'content' => $this->content,
            'preview' => $this->preview,
            'price' => $this->price,
            'slug' => $this->slug,
            'category' => new CategoryResource($this->category),
            'review' => $customerReview ? new ReviewResource($customerReview) : null,
        ];
    }
}
