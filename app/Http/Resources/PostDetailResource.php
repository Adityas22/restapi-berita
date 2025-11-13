<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use Dom\Comment;

class PostDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'title' => $this->title,
            // 'image' => $this->image,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'content' => $this->content,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d'),
            'author' => $this->author,
            // eiger loading, menggunakan whenLoaded untuk relationship saja
            'writer' => $this->whenLoaded('writer'),
             // Tambahkan total komentar
            'total_comments' => $this->whenLoaded('comments', fn() => $this->comments->count()),
            'comments'=>CommentResource::collection($this->whenLoaded('comments'))
        ];
    }
}