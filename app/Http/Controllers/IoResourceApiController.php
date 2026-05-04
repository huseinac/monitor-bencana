<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class IoResourceApiController extends Controller
{
    protected $service;

    protected $itemVariable = 'item';

    public function index(Request $request)
    {
        $items = $this->service->search($request->all());
        return response()->json(['success' => true, 'message' => $this->itemVariable . ' found', 'data' => $items]);
    }

    public function store(Request $request)
    {
        $item = $this->service->store($request->all());
        return response()->json(['success' => true, 'message' => 'Stored successfully', 'data' =>$item]);
    }

    public function show($id)
    {
        $item = $this->service->find($id);
        return response()->json(['success' => true, 'message' => 'Data found', 'data' =>$item]);
    }

    public function update(Request $request, $id)
    {
        $item = $this->service->update($request->all(), $id);
        return response()->json(['success' => true, 'message' => 'Updated successfully', 'data' =>$item]);
    }

    public function destroy($id)
    {
        $item = $this->service->delete($id);
        if (!empty($item['errors'])) return response()->json(['success' => false, 'message' => $item['error']]);
        return response()->json(['success' => true, 'message' => 'Deleted successfully', 'data' =>$item]);
    }

    public function restore($id)
    {
        $item = $this->service->restore($id);
        return response()->json(['success' => true, 'message' => 'Restored successfully', 'data' =>$item]);
    }
}
