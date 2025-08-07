<?php

namespace App\Http\Controllers;

use App\Services\ProductProfileService;
use Illuminate\Http\Request;

class ProductProfileController extends BaseController
{
    protected $productProfileService;

    public function __construct(ProductProfileService $productProfileService)
    {
        $this->productProfileService = $productProfileService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $productProfiles = $this->productProfileService->getAll();
            return $this->sendResponse($productProfiles, 'Product profiles retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving product profiles', [$e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $productProfile = $this->productProfileService->create($request->all());
            return $this->sendResponse($productProfile, 'Product profile created successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error creating product profile', [$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $productProfile = $this->productProfileService->findById($id);
            if (!$productProfile) {
                return $this->sendError('Product profile not found');
            }
            return $this->sendResponse($productProfile, 'Product profile retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving product profile', [$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $productProfile = $this->productProfileService->update($id, $request->all());
            if (!$productProfile) {
                return $this->sendError('Product profile not found');
            }
            return $this->sendResponse($productProfile, 'Product profile updated successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error updating product profile', [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $result = $this->productProfileService->delete($id);
            if (!$result) {
                return $this->sendError('Product profile not found');
            }
            return $this->sendResponse([], 'Product profile deleted successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error deleting product profile', [$e->getMessage()]);
        }
    }
}
