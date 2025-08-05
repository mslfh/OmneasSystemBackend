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

    public function index()
    {
        try {
            $categories = $this->categoryService->findAll();
            return $this->sendResponse($categories, 'Categories retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving categories', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $category = $this->categoryService->findById($id);
            if (!$category) {
                return $this->sendError('Category not found');
            }
            return $this->sendResponse($category, 'Category retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving category', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $category = $this->categoryService->create($request->all());
            return $this->sendResponse($category, 'Category created successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error creating category', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = $this->categoryService->update($id, $request->all());
            if (!$category) {
                return $this->sendError('Category not found');
            }
            return $this->sendResponse($category, 'Category updated successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error updating category', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $result = $this->categoryService->delete($id);
            if (!$result) {
                return $this->sendError('Category not found');
            }
            return $this->sendResponse([], 'Category deleted successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error deleting category', $e->getMessage());
        }
    }

    public function getActiveCategories()
    {
        try {
            $categories = $this->categoryService->getActiveCategories();
            return $this->sendResponse($categories, 'Active categories retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving active categories', [$e->getMessage()]);
        }
    }

    public function getByParentId($parentId)
    {
        try {
            $categories = $this->categoryService->findByParentId($parentId);
            return $this->sendResponse($categories, 'Categories retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving categories', [$e->getMessage()]);
        }
    }
}
