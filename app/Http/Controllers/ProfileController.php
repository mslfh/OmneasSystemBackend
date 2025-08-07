<?php

namespace App\Http\Controllers;

use App\Services\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends BaseController
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $profiles = $this->profileService->getAll();
            return $this->sendResponse($profiles, 'Profiles retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving profiles', [$e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $profile = $this->profileService->create($request->all());
            return $this->sendResponse($profile, 'Profile created successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error creating profile', [$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $profile = $this->profileService->findById($id);
            if (!$profile) {
                return $this->sendError('Profile not found');
            }
            return $this->sendResponse($profile, 'Profile retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving profile', [$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $profile = $this->profileService->update($id, $request->all());
            if (!$profile) {
                return $this->sendError('Profile not found');
            }
            return $this->sendResponse($profile, 'Profile updated successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error updating profile', [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $result = $this->profileService->delete($id);
            if (!$result) {
                return $this->sendError('Profile not found');
            }
            return $this->sendResponse([], 'Profile deleted successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error deleting profile', [$e->getMessage()]);
        }
    }
}
