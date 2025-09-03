<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Services\ItemService;
use Illuminate\Http\Request;

class ItemController extends BaseController
{
    protected $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
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

        $items = $this->itemService->getPaginatedItems($start, $count, $filter, $sortBy, $descending, $selected);

        return response()->json([
            'rows' => $items['data'],
            'total' => $items['total'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // This method is typically used for returning forms in web applications
        // For API, you can return validation rules or empty response
        return $this->sendResponse([], 'Create form data');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $item = $this->itemService->createItem($request->all());
            return $this->sendResponse($item, 'Item created successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error creating item', [$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $item = $this->itemService->getItemById($id);
            if (!$item) {
                return $this->sendError('Item not found');
            }
            return $this->sendResponse($item, 'Item retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving item', [$e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $item = $this->itemService->getItemById($id);
            if (!$item) {
                return $this->sendError('Item not found');
            }
            return $this->sendResponse($item, 'Edit form data retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving item for edit', [$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $item = $this->itemService->updateItem($id, $request->all());
            if (!$item) {
                return $this->sendError('Item not found');
            }
            return $this->sendResponse($item, 'Item updated successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error updating item', [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $result = $this->itemService->deleteItem($id);
            if (!$result) {
                return $this->sendError('Item not found');
            }
            return $this->sendResponse([], 'Item deleted successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error deleting item', [$e->getMessage()]);
        }
    }

    /**
     * Get items by field value.
     */
    public function getByField(Request $request)
    {
        try {
            $field = $request->input('field');
            $value = $request->input('value');

            if (!$field || !$value) {
                return $this->sendError('Field and value parameters are required');
            }

            $items = $this->itemService->findByField($field, $value);
            return $this->sendResponse($items, 'Items retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving items', [$e->getMessage()]);
        }
    }

    /**
     * Get items by price range.
     */
    public function getByPriceRange(Request $request)
    {
        try {
            $minPrice = $request->input('min_price', 0);
            $maxPrice = $request->input('max_price', PHP_FLOAT_MAX);
            $items = $this->itemService->findByPriceRange($minPrice, $maxPrice);
            return $this->sendResponse($items, 'Items retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving items', [$e->getMessage()]);
        }
    }

    /**
     * Check if item exists.
     */
    public function exists($id)
    {
        try {
            $exists = $this->itemService->exists($id);
            return $this->sendResponse(['exists' => $exists], 'Item existence checked successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error checking item existence', [$e->getMessage()]);
        }
    }

    /**
     * Get total item count.
     */
    public function count()
    {
        try {
            $count = $this->itemService->count();
            return $this->sendResponse(['count' => $count], 'Item count retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving item count', [$e->getMessage()]);
        }
    }

    /**
     * Get active items.
     */
    public function getActiveItems()
    {
        try {
            $items = $this->itemService->getActiveItems();
            return $this->sendResponse($items, 'Active items retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving active items', [$e->getMessage()]);
        }
    }

    public function getBulkItemsByIds(Request $request)
    {
        try {
            $ids = $request->input('ids', []);
            $ids = is_array($ids) ? $ids : explode(',', $ids);
            $items = $this->itemService->getBulkItemsByIds($ids);
            return $this->sendResponse($items, 'Bulk items retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving bulk items', [$e->getMessage()]);
        }
    }

}
