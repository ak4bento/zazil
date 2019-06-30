<?php
namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Exceptions\DataEmptyException;
use App\Events\ItemUpdated;
use App\Repositories\ItemRepository;

class ItemService extends BaseService
{
    public function __construct()
    {
        $this->model = new Item;
        $this->repository = new ItemRepository;
    }

    public function setComplete($items, $complete = true) {
        $response = [];
        foreach ($items as $item) {
            $data = $this->model->findOrFail($item['item_id']);
            $data->update([
                'is_completed' => !!$complete,
                'completed_at' => $complete ? date('Y-m-d H:i:s') : null
            ]);
            $response[] = [
                'id' => $data->id,
                'item_id' => $data->id,
                'is_completed' => $data->is_completed,
                'checklist_id' => $data->checklist_id,
            ];
        }
        return [
            'data' => $response
        ];
    }

    public function create(Request $request, $checklistId)
    {
//        $this->validate($request, [
//            'data.attribute.description' => 'required',
//            'data.attribute.due' => 'nullable|date_format:Y-m-d H:i:s'
//        ]);
        $attribute = $request->data['attribute'];
        $data = $this->model->create([
            'checklist_id' => $checklistId,
            'description' => $attribute['description'],
            'due' => isset($attribute['due']) ? $attribute['due'] : null,
            'urgency' => isset($attribute['urgency']) ? $attribute['urgency'] : null,
            'assignee_id' => isset($attribute['assignee_id']) ? $attribute['assignee_id'] : null,
            'task_id' => isset($attribute['task_id']) ? $attribute['task_id'] : null,
        ]);

        return $this->repository->getSingleData($checklistId, $data->id);
    }

    public function update(Request $request, $checklistId, $id)
    {
//        $this->validate($request, [
//            'data.attribute.description' => 'required',
//            'data.attribute.due' => 'nullable|date_format:Y-m-d H:i:s'
//        ]);

        $attribute = $request->data['attribute'];
        $data = $this->model->findOrFail($id);
        $data->update([
            'description' => $attribute['description'],
            'due' => isset($attribute['due']) ? $attribute['due'] : null,
            'urgency' => isset($attribute['urgency']) ? $attribute['urgency'] : null,
            'assignee_id' => isset($attribute['assignee_id']) ? $attribute['assignee_id'] : null,
            'task_id' => isset($attribute['task_id']) ? $attribute['task_id'] : null,
        ]);

        return $this->repository->getSingleData($checklistId, $data->id);
    }

    public function delete($id)
    {
        $data = $this->model->findOrFail($id);
        $data->update([
            'deleted_at'	=>	date('Y-m-d H:i:s'),
            'deleted_by' 	=> 	'1',
        ]);

        return null;
    }
}
