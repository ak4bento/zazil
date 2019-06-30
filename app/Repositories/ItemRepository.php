<?php
namespace App\Repositories;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Exceptions\DataEmptyException;

class ItemRepository extends BaseRepository
{
    static $module = 'item';

    public function __construct()
    {
        $this->model = new Item;
    }

    public function getIndexData($params, $checklistId)
    {
        $page_offset = $params->has('page_offset') ? $params->page_offset : 0;
        $page_limit = $params->has('page_limit') ? $params->page_limit : 10;

        $data = $this->model->where('checklist_id', $checklistId);
        if ($params->has('sort')) {
            $data = $data->orderBy(str_replace('-' ,'', $params->sort), $params->sort[0] === '-' ? 'desc' : 'asc');
        }
        if ($params->has('filter') && in_array((key($params->filter)), ['description', 'is_completed', 'completed_at'])) {
            $search_type = key(array_values($params->filter)[0]);
            $field = key($params->filter);
            $keyword = array_values($params->filter)[0][$search_type];
            if (strpos($search_type, 'like') !== false) {
                if ($keyword[0] === '*') {
                    $keyword = '%'.$keyword;
                } else if (substr($keyword[0], -1) === '*') {
                    $keyword = $keyword.'%';
                } else {
                    $keyword = '%'.$keyword.'%';
                }
                $data = $data->where($field, $search_type === '!like' ? 'not like' : 'like', $keyword);
            }
            else if ($search_type === 'is') {
                $data = $data->where($field, $keyword);
            } else if ($search_type === '!is') {
                $data = $data->where($field, '!=', $keyword);
            } else if ($search_type === 'in') {
                $data = $data->whereIn($field, explode(',', $keyword));
            } else if ($search_type === '!in') {
                $data = $data->whereNotIn($field, explode(',', $keyword));
            }
        }
        $total = $data->count('id');
        $count = ceil($total/$page_limit);

        $d = $data->offset($page_offset)->limit($page_limit)->first();
        $item = [];
        foreach ($data->offset($page_offset)->limit($page_limit)->get() as $all) {
            $item[] = $all;
        }
        $attributes = [
            'description' => $d->description,
            'is_completed' => $d->is_completed,
            'completed_at' => $d->completed_at,
            'due' => $d->due,
            'urgency' => $d->urgency,
            'updated_by' => $d->updated_by,
            'assignee_id' => $d->assignee_id,
            'task_id' => $d->task_id,
            'created_at' => $d->created_at,
            'updated_at' => $d->updated_at,
            'item' => $item
        ];
        $content = [
            'type'=> 'items',
            'id'=> $d->id,
            'attributes' => $attributes,
            'links' => url("/checklists/$d->checklist_id/items")
        ];

        return [
            'data' => $content
        ];
    }

    public function getSingleData($checklistId, $id)
    {
        $data = $this->model->where('checklist_id', $checklistId)->where('id', $id)->first();
        $attributes = [
            'description' => $data->description,
            'is_completed' => $data->is_completed,
            'completed_at' => $data->completed_at,
            'due' => $data->due,
            'urgency' => $data->urgency,
            'checklist_id' => $data->checklist_id,
            'assignee_id' => $data->assignee_id,
            'task_id' => $data->task_id,
            'created_at' => $data->created_at,
            'created_by' => $data->created_by,
            'updated_at' => $data->updated_at,
            'updated_by' => $data->updated_by,
            'deleted_at' => $data->deleted_at,
            'deleted_by' => $data->deleted_by,
        ];
        return [
            'data' => [
                'type'=> 'items',
                'id'=> $data->id,
                'attributes' => $attributes,
                'links' => [
                    'self' => url("/checklists/$data->checklist_id/items/$id")
                ]
            ]
        ];
    }

}
