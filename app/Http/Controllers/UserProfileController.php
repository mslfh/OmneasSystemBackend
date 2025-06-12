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
    public function index(Request $request)
    {
        $start = $request->query('start', 0);
        $count = $request->query('count', 10);
        $filter = $request->query('filter', null);
        $sortBy = $request->query('sortBy', 'id');
        $descending = $request->query('descending', false);
        $users = $this->userProfileService->getPaginatedProfiles($start, $count, $filter, $sortBy, $descending);
        return response()->json([
            'rows' => $users['data'],
            'total' => $users['total'],
        ]);
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
     * Get the profile by user ID.
     */
    public function getProfileByUser(Request $request)
    {
        $userId = $request->query('user_id');
        $profile = $this->userProfileService->getProfileByUserId($userId);
        return response()->json($profile);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $files = $request->file('files');
        $data = $request->validate([
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
        $profile = $this->userProfileService->update($id,  $data);
        if ($files) {
            $medical_attachment_path = $profile->getRawOriginal('medical_attachment_path') ?? [];
            $medical_attachment_path = is_string($medical_attachment_path) ? json_decode($medical_attachment_path, true) : $medical_attachment_path;
            if (empty($medical_attachment_path) || !is_array($medical_attachment_path)) {
                $medical_attachment_path = [];
            }
            $paths = [];
            foreach ($files as $file) {
                $path = $file->store('profileAttachments', 'public');
                $paths[] = $path;
            }
            $medical_attachment_path = array_merge($medical_attachment_path, $paths);
            $profile->medical_attachment_path = $medical_attachment_path;
            $profile->save();
        }
        return response()->json($profile);
    }

    /**
     * Upload an attachment to the user profile.
     */

    public function uploadAttachment(Request $request, $id)
    {
        $file = $request->file('file');
        if ($file) {
            $path = $file->store('profileAttachments', 'public');
            $profile = $this->userProfileService->find($id);
            $medical_attachment_path = $profile->getRawOriginal('medical_attachment_path') ?? [];
            $medical_attachment_path = is_string($medical_attachment_path) ? json_decode($medical_attachment_path, true) : $medical_attachment_path;
            if (empty($medical_attachment_path) || !is_array($medical_attachment_path)) {
                $medical_attachment_path = [];
            }
            $medical_attachment_path[] = $path;
            $profile->medical_attachment_path = $medical_attachment_path;
            $profile->save();
            return response()->json($profile);
        }
        return response()->json(['error' => 'File not found'], 404);
    }

    /**
     * Delete an attachment from the user profile.
     */
    public function deleteAttachment(Request $request, $id)
    {
        $filePath = $request->input('file_path');
        if (!$filePath) {
            return response()->json(['error' => 'File path is required'], 400);
        }

        $profile = $this->userProfileService->find($id);
        $medical_attachment_path = $profile->getRawOriginal('medical_attachment_path') ?? [];
        $medical_attachment_path = is_string($medical_attachment_path) ? json_decode($medical_attachment_path, true) : $medical_attachment_path;

        if (($key = array_search($filePath, $medical_attachment_path)) !== false) {
            unset($medical_attachment_path[$key]);
            $profile->medical_attachment_path = array_values($medical_attachment_path);
            $profile->save();
            // Optionally delete the file from storage
            if (\Storage::disk('public')->exists($filePath)) {
                \Storage::disk('public')->delete($filePath);
            }
            // Return the updated profile
            return response()->json($profile->medical_attachment_path);
        }

        return response()->json(['error' => 'File not found in profile'], 404);
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
