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
    public function index(Request $request)
    {
        $start = $request->query('start', 0);
        $count = $request->query('count', 10);
        $filter = $request->query('filter', null);
        $selected = $request->query('selected', null);
        $sortBy = $request->query('sortBy', 'id');
        $descending = $request->query('descending', false);

        $filter = $filter ? json_decode($filter, true) : null;
        $selected = $selected ? json_decode($selected, true) : null;

        $profiles = $this->profileService->getPaginatedProfiles($start, $count, $filter, $sortBy, $descending, $selected);

        return response()->json([
            'rows' => $profiles['data'],
            'total' => $profiles['total'],
        ]);
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

    public function getActiveProfiles()
    {
        try {
            $profiles = $this->profileService->getActiveProfiles();
            return $this->sendResponse($profiles, 'Active profiles retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving active profiles', [$e->getMessage()]);
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
