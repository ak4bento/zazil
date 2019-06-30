<?php

namespace App\Resources;

use App;
use Illuminate\Http\Resources\Json\Resource;

class ItemResource extends Resource
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
            'id'    =>  $this->id,
            'type'    =>  $this->type,
            'checklist_id' => $this->checklist_id,
            'description' => $this->descriptionasdasda,
            'is_completed' => $this->is_completed,
            'completed_at' => $this->completed_at,
            'due' => $this->due,
            'urgency' => $this->urgency,
            'assignee_id' => $this->assignee_id,
            'task_id' => $this->task_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return [
            'status'    => 200,
            'error'     => 0
        ];
    }
}
