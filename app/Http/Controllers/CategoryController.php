<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
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

        $categories = $this->categoryService->getPaginatedCategories($start, $count, $filter, $sortBy, $descending, $selected);

        return response()->json([
            'rows' => $categories['data'],
            'total' => $categories['total'],
        ]);
    }


    public function getAllCategories(Request $request)
    {
        // try {
        dd('ok');
            $categories = $this->categoryService->getAllCategories();

            return $this->sendResponse($categories, 'All categories retrieved successfully');
        // } catch (\Exception $e) {
            // return $this->sendError('Error retrieving categories', [$e->getMessage()]);
        // }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $category = $this->categoryService->createCategory($request->all());
            return $this->sendResponse($category, 'Category created successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error creating category', [$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $category = $this->categoryService->getCategoryById($id);
            if (!$category) {
                return $this->sendError('Category not found');
            }
            return $this->sendResponse($category, 'Category retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving category', [$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $category = $this->categoryService->updateCategory($id, $request->all());
            if (!$category) {
                return $this->sendError('Category not found');
            }
            return $this->sendResponse($category, 'Category updated successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error updating category', [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $result = $this->categoryService->deleteCategory($id);
            if (!$result) {
                return $this->sendError('Category not found');
            }
            return $this->sendResponse([], 'Category deleted successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error deleting category', [$e->getMessage()]);
        }
    }

    /**
     * Get active categories.
     */
    public function getActiveCategories()
    {
        try {
            $categories = $this->categoryService->getActiveCategories();
            return $this->sendResponse($categories, 'Active categories retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving active categories', [$e->getMessage()]);
        }
    }

    /**
     * Get categories by parent ID.
     */
    public function getByParentId($parentId)
    {
        try {
            $categories = $this->categoryService->findByParentId($parentId);
            return $this->sendResponse($categories, 'Categories retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving categories', [$e->getMessage()]);
        }
    }

    /**
     * Get categories by field value.
     */
    public function getByField(Request $request)
    {
        try {
            $field = $request->input('field');
            $value = $request->input('value');

            if (!$field || !$value) {
                return $this->sendError('Field and value parameters are required');
            }

            $categories = $this->categoryService->findByField($field, $value);
            return $this->sendResponse($categories, 'Categories retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving categories', [$e->getMessage()]);
        }
    }

    /**
     * Check if category exists.
     */
    public function exists($id)
    {
        try {
            $exists = $this->categoryService->exists($id);
            return $this->sendResponse(['exists' => $exists], 'Category existence checked successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error checking category existence', [$e->getMessage()]);
        }
    }

    /**
     * Get total category count.
     */
    public function count()
    {
        try {
            $count = $this->categoryService->count();
            return $this->sendResponse(['count' => $count], 'Category count retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving category count', [$e->getMessage()]);
        }
    }
}

