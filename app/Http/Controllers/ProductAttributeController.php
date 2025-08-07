<?php

namespace App\Http\Controllers;

use App\Services\ProductAttributeService;
use Illuminate\Http\Request;

class ProductAttributeController extends BaseController
{
    protected $productAttributeService;

    public function __construct(ProductAttributeService $productAttributeService)
    {
        $this->productAttributeService = $productAttributeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $productAttributes = $this->productAttributeService->getAll();
            return $this->sendResponse($productAttributes, 'Product attributes retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving product attributes', [$e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $productAttribute = $this->productAttributeService->create($request->all());
            return $this->sendResponse($productAttribute, 'Product attribute created successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error creating product attribute', [$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $productAttribute = $this->productAttributeService->findById($id);
            if (!$productAttribute) {
                return $this->sendError('Product attribute not found');
            }
            return $this->sendResponse($productAttribute, 'Product attribute retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving product attribute', [$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $productAttribute = $this->productAttributeService->update($id, $request->all());
            if (!$productAttribute) {
                return $this->sendError('Product attribute not found');
            }
            return $this->sendResponse($productAttribute, 'Product attribute updated successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error updating product attribute', [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $result = $this->productAttributeService->delete($id);
            if (!$result) {
                return $this->sendError('Product attribute not found');
            }
            return $this->sendResponse([], 'Product attribute deleted successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error deleting product attribute', [$e->getMessage()]);
        }
    }

    /**
     * Get product attributes by product ID.
     */
    public function getByProductId($productId)
    {
        try {
            $productAttributes = $this->productAttributeService->findByProductId($productId);
            return $this->sendResponse($productAttributes, 'Product attributes retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving product attributes', [$e->getMessage()]);
        }
    }
}
