<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CategoryResource;

class ProductWithCategoryAndFilterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'amount' => $this->amount,
            'content' => $this->content,
            'preview' => $this->preview,
            'price' => $this->price,
            'category' => new CategoryResource($this->category),
            'category_filter_id' => $this->category_filter_id,
            'category_filter_title' => $this->category_filter_title,
        ];
    }
}
