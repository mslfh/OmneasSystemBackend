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

        $products = $this->productService->getPaginatedProducts($start, $count, $filter, $sortBy, $descending, $selected);

        return response()->json([
            'rows' => $products['data'],
            'total' => $products['total'],
        ]);
    }

    /**
     * Get active products.
     */
    public function getActiveProducts()
    {
        try {
            $products = $this->productService->getAllProducts();
            return $this->sendResponse($products, 'Active products retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving active products', [$e->getMessage()]);
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
            $product = $this->productService->getProductInfoById($id);
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

    public function getAllProducts()
    {
        try {
            $products = $this->productService->getAllForClient();
            return $this->sendResponse($products, 'Products retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving products', [$e->getMessage()]);
        }
    }

    public function getProductById($id)
    {
        try {
            $product = $this->productService->getProductByIdForClient($id);
            return $this->sendResponse($product, 'Product retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving products', [$e->getMessage()]);
        }
    }

    public function getProductCustomization($id)
    {
        try {
            $customization = $this->productService->getProductCustomization($id);
            return $this->sendResponse($customization, 'Product customization retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving product customization', [$e->getMessage()]);
        }
    }
}
