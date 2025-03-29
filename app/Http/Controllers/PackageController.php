<?php

namespace App\Http\Controllers;

use App\Services\PackageService;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    protected $packageService;

    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
    }

    public function index()
    {
        return response()->json($this->packageService->getAllPackages());
    }

    public function getPackageWithService()
    {
        return response()->json($this->packageService->getPackageWithService());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'hint' => 'nullable|string|max:255',
            'status' => 'required|string|in:active,inactive',
        ]);

        return response()->json($this->packageService->createPackage($data), 201);
    }

    public function show($id)
    {
        return response()->json($this->packageService->getPackageById($id));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'hint' => 'nullable|string|max:255',
            'status' => 'sometimes|required|string|in:active,inactive',
        ]);

        return response()->json($this->packageService->updatePackage($id, $data));
    }

    public function destroy($id)
    {
        $this->packageService->deletePackage($id);
        return response()->json(null, 204);
    }
}
