<?php

namespace App\Http\Controllers;

use App\Services\SystemSettingService;
use Illuminate\Http\Request;

class SystemSettingController extends BaseController
{
    protected $systemSettingService;

    public function __construct(SystemSettingService $systemSettingService)
    {
        parent::__construct();
        $this->systemSettingService = $systemSettingService;
    }

    public function index()
    {
        return response()->json($this->systemSettingService->getAllSettings());
    }

    public function show($id)
    {
        return response()->json($this->systemSettingService->getSettingById($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key' => 'required|string|unique:system_settings,key',
            'value' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        return response()->json($this->systemSettingService->createSetting($data), 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'key' => 'sometimes|string|unique:system_settings,key,' . $id,
            'value' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        return response()->json($this->systemSettingService->updateSetting($id, $data));
    }
    public function destroy($id)
    {
        $this->systemSettingService->deleteSetting($id);
        return response()->json(null, 204);
    }
}
