<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TaskCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($task) {
                return [
                    'id'          => $task->id,
                    'title'       => $task->title,
                    'status'      => $task->status,
                    'due_date'    => $task->due_date,
                ];
            }),
            'meta' => [
                'total'        => $this->total(),
                'per_page'     => $this->perPage(),
                'current_page' => $this->currentPage(),
            ],
        ];
    }
}
