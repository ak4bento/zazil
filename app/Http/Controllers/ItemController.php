<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;
use App\Services\ItemService;
use App\Models\Item;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->service = new ItemService;
    }

    public function index(Request $request, $checklistId)
    {
        $data = $this->service->getIndexData($request, $checklistId);

        return response()->json($data);
    }

    public function show($checklistId, $itemId)
    {
        $data = $this->service->getSingleData($checklistId, $itemId);

        return response()->json($data);
    }

    public function setComplete(Request $request)
    {
        $this->validate($request, [
            'data.*.item_id' => 'required|exists:items,id'
        ]);
        $data = $this->service->setComplete($request->data);
        return response()->json($data);
    }

    public function setIncomplete(Request $request)
    {
        $this->validate($request, [
            'data.*.item_id' => 'required|exists:items,id'
        ]);
        $data = $this->service->setComplete($request->data, false);

        return response()->json($data);
    }

    public function create(Request $request, $checklistId)
    {
        $data = $this->service->create($request, $checklistId);

        return response()->json($data, 201);
    }

    public function update(Request $request, $checklistId, $id)
    {
        $data = $this->service->update($request, $checklistId, $id);

        return response()->json($data, 201);
    }

    public function destroy($id)
    {
        $data = $this->service->delete($id);
        return response()->json($data, 204);
    }
}
