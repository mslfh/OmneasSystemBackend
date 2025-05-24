<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Services\UserProfileService;
use App\Http\Requests\UserProfileRequest;

class UserProfileController
{
    protected $userProfileService;

    public function __construct(UserProfileService $userProfileService)
    {
        $this->userProfileService = $userProfileService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profiles = $this->userProfileService->all();
        return response()->json($profiles);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $files = $request->file('files');

        $data = $request->validate([
            'user_id' => 'nullable',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string',
            'gender' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'visit_reason' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string',
            'emergency_contact_phone' => 'nullable|string',
            'private_health_fund_provider' => 'nullable|string',
            'pain_points' => 'nullable|string',
            'areas_of_soreness' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'others' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        if ($files) {
            foreach ($files as $file) {
                $path = $file->store('profileAttachments', 'public');
                $data['medical_attachment_path'][] = $path;
            }
        }
        $profile = $this->userProfileService->create($data);
        return response()->json($profile, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $profile = $this->userProfileService->find($id);
        return response()->json($profile);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserProfile $userProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserProfileRequest $request, $id)
    {
        $profile = $this->userProfileService->update($id, $request->validated());
        return response()->json($profile);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->userProfileService->delete($id);
        return response()->json(null, 204);
    }
}
