<?php

namespace App\Http\Controllers;

use App\Services\ProductItemService;
use App\Http\Requests\ProductItemRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Class ProductItemController
 *
 * Handles HTTP requests related to product items.
 * Follows the Service-Repository pattern for clean architecture.
 *
 * @package App\Http\Controllers
 */
class ProductItemController extends BaseController
{
    /**
     * The product item service instance.
     *
     * @var ProductItemService
     */
    protected ProductItemService $productItemService;

    /**
     * Create a new controller instance.
     *
     * @param ProductItemService $productItemService
     */
    public function __construct(ProductItemService $productItemService)
    {
        $this->productItemService = $productItemService;
    }

    /**
     * Display a listing of all product items.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $productItems = $this->productItemService->getAll();
            return $this->sendResponse($productItems, 'Product items retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving product items', [$e->getMessage()]);
        }
    }

    /**
     * Store a newly created product item in storage.
     *
     * @param ProductItemRequest $request
     * @return JsonResponse
     */
    public function store(ProductItemRequest $request): JsonResponse
    {
        try {
            $productItem = $this->productItemService->create($request->validated());
            return $this->sendResponse($productItem, 'Product item created successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error creating product item', [$e->getMessage()]);
        }
    }

    /**
     * Display the specified product item.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $productItem = $this->productItemService->findById($id);
            if (!$productItem) {
                return $this->sendError('Product item not found');
            }
            return $this->sendResponse($productItem, 'Product item retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving product item', [$e->getMessage()]);
        }
    }

    /**
     * Update the specified product item in storage.
     *
     * @param ProductItemRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ProductItemRequest $request, int $id): JsonResponse
    {
        try {
            $productItem = $this->productItemService->update($id, $request->validated());
            if (!$productItem) {
                return $this->sendError('Product item not found');
            }
            return $this->sendResponse($productItem, 'Product item updated successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error updating product item', [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified product item from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $result = $this->productItemService->delete($id);
            if (!$result) {
                return $this->sendError('Product item not found');
            }
            return $this->sendResponse([], 'Product item deleted successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error deleting product item', [$e->getMessage()]);
        }
    }

    /**
     * Get product items by product ID.
     *
     * @param int $productId
     * @return JsonResponse
     */
    public function getByProductId(int $productId): JsonResponse
    {
        try {
            $productItems = $this->productItemService->findByProductId($productId);
            return $this->sendResponse($productItems, 'Product items retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving product items', [$e->getMessage()]);
        }
    }
}
