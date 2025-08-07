<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = $this->productService->getAllProducts();
            return $this->sendResponse($products, 'Products retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving products', [$e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $product = $this->productService->createProduct($request->all());
            return $this->sendResponse($product, 'Product created successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error creating product', [$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $product = $this->productService->getProductById($id);
            if (!$product) {
                return $this->sendError('Product not found');
            }
            return $this->sendResponse($product, 'Product retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving product', [$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $product = $this->productService->updateProduct($id, $request->all());
            if (!$product) {
                return $this->sendError('Product not found');
            }
            return $this->sendResponse($product, 'Product updated successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error updating product', [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $result = $this->productService->deleteProduct($id);
            if (!$result) {
                return $this->sendError('Product not found');
            }
            return $this->sendResponse([], 'Product deleted successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error deleting product', [$e->getMessage()]);
        }
    }

    /**
     * Get products by category.
     */
    public function getByCategory($category)
    {
        try {
            $products = $this->productService->findByCategory($category);
            return $this->sendResponse($products, 'Products retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving products', [$e->getMessage()]);
        }
    }

    /**
     * Get products by price range.
     */
    public function getByPriceRange(Request $request)
    {
        try {
            $minPrice = $request->input('min_price', 0);
            $maxPrice = $request->input('max_price', PHP_FLOAT_MAX);
            $products = $this->productService->findByPriceRange($minPrice, $maxPrice);
            return $this->sendResponse($products, 'Products retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving products', [$e->getMessage()]);
        }
    }

    /**
     * Get products by field value.
     */
    public function getByField(Request $request)
    {
        try {
            $field = $request->input('field');
            $value = $request->input('value');

            if (!$field || !$value) {
                return $this->sendError('Field and value parameters are required');
            }

            $products = $this->productService->findByField($field, $value);
            return $this->sendResponse($products, 'Products retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving products', [$e->getMessage()]);
        }
    }

    /**
     * Check if product exists.
     */
    public function exists($id)
    {
        try {
            $exists = $this->productService->exists($id);
            return $this->sendResponse(['exists' => $exists], 'Product existence checked successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error checking product existence', [$e->getMessage()]);
        }
    }

    /**
     * Get total product count.
     */
    public function count()
    {
        try {
            $count = $this->productService->count();
            return $this->sendResponse(['count' => $count], 'Product count retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving product count', [$e->getMessage()]);
        }
    }
}
