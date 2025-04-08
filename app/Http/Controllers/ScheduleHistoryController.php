<?php

namespace App\Http\Controllers;

use App\Services\ScheduleHistoryService;
use Illuminate\Http\Request;

class ScheduleHistoryController extends BaseController
{
    protected $service;

    public function __construct(ScheduleHistoryService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->getAll());
    }

    public function show($id)
    {
        return response()->json($this->service->getById($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'working_datetime' => 'required|string',
            'break_datetime' => 'required|string',
            'event_datetime' => 'required|string',
        ]);

        return response()->json($this->service->create($data), 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'staff_id' => 'sometimes|exists:staff,id',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
            'working_datetime' => 'sometimes|string',
            'break_datetime' => 'sometimes|string',
            'event_datetime' => 'sometimes|string',
        ]);

        return response()->json($this->service->update($id, $data));
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(null, 204);
    }
}
