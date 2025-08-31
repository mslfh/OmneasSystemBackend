<?php

namespace App\Services;

use App\Contracts\ProductContract;
use DB;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductContract $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Get all products
     */
    public function getAllProducts()
    {
        return $this->productRepository->getAll();
    }

    /**
     * Get product by ID for admin
     */
    public function getProductById($id)
    {
        return $this->productRepository->findById($id);
    }

    /**
     * Get all products for client
     */
    public function getAllForClient()
    {
        $data = $this->productRepository->getAll();
        return $data->map(function ($product) {
            return [
                'id' => $product->id,
                'title' => $product->title,
                'code' => $product->code,
                'description' => $product->description,
                'price' => $product->price,
                'discount' => $product->discount,
                'selling_price' => $product->selling_price,
                'image' => $product->image,
                'stock' => $product->stock,
                'tag' => $product->tag,
                'sort' => $product->sort,
                'customizable' => $product->customizable,
                'is_featured' => $product->is_featured,
            ];
        });
    }

    /**
     * Get product info by ID for client
     */
    public function getProductByIdForClient($id)
    {
        $product = $this->productRepository->findById($id);
        if (!$product) {
            return null;
        }
        $product->load(['categories', 'items', 'customizationItems']);

        // 只返回关键字段，排除时间戳和 status
        return [
            'id' => $product->id,
            'title' => $product->title,
            'code' => $product->code,
            'description' => $product->description,
            'price' => $product->price,
            'discount' => $product->discount,
            'selling_price' => $product->selling_price,
            'image' => $product->image,
            'stock' => $product->stock,
            'tag' => $product->tag,
            'sort' => $product->sort,
            'customizable' => $product->customizable,
            'is_featured' => $product->is_featured,
            'categories' => $product->categories->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                ];
            })
        ];
    }

     /**
     * Get product Customization by ID for client
     */
    public function getProductCustomization($id)
    {
        $product = $this->productRepository->findById($id);
        if (!$product) {
            return null;
        }
        $product->load(['items', 'customizationItems']);

        // 只返回关键字段，排除时间戳和 status
        return [
            'id' => $product->id,
            'stock' => $product->stock,
            'items' => $product->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'unit' => $item->unit,
                    'quantity' => $item->quantity,
                ];
            }),
            'customizationItems' => $product->customizationItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'item_id' => $item->item_id,
                    'mode' => $item->mode,
                    'replacement_list' => $item->replacement_list,
                    'replacement_diff' => $item->replacement_diff,
                    'replacement_extra' => $item->replacement_extra,
                    'quantity_price' => $item->quantity_price,
                ];
            }),
        ];
    }

    /**
     * Create new product
     */
    public function createProduct(array $data)
    {

        $productData = [];
        $categories = $data['categories'] ?? [];
        unset($data['categories']);

        $productItems = $data['ingredients'] ?? [];
        unset($data['ingredients']);

        $customizationItems = $data['customizations'] ?? [];
        unset($data['customizations']);

        if ($data['customizable']) {
            $productData['customizable'] = true;
        }
        $productData = $data;

        DB::beginTransaction();

        try {

            $product = $this->productRepository->create($productData);

            if ($categories) {
                $product->categories()->sync($categories);
            }
            if ($productItems) {
                foreach ($productItems as $item) {
                    $data = [];
                    $data['product_id'] = $product->id;
                    $data['item_id'] = $item['id'];
                    $data['unit'] = $item['unit'];
                    $data['quantity'] = $item['quantity'];
                    $product->productItems()->create($data);
                }
            }
            if ($customizationItems) {
                foreach ($customizationItems as $item) {
                    $data = [];
                    $data['product_id'] = $product->id;
                    $data['item_id'] = $item['ingredientId'];
                    $data['mode'] = $item['mode'];
                    $data['replacement_list'] = $item['enabledReplacements'];
                    $data['replacement_diff'] = $item['replacements'];
                    $data['replacement_extra'] = $item['replacementExtras'];
                    $data['quantity_price'] = $item['quantityPricing'];
                    $product->customizationItems()->create($data);
                }
            }
            DB::commit();
            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update product
     */
    public function updateProduct($id, array $data)
    {
        return $this->productRepository->update($id, $data);
    }

    /**
     * Delete product
     */
    public function deleteProduct($id)
    {
        return $this->productRepository->delete($id);
    }

    /**
     * Collection of products in the specified category
     */
    public function findByCategory(string $category)
    {
        return $this->productRepository->findByCategory($category);
    }

    /**
     * Collection of products within price range
     */
    public function findByPriceRange(float $minPrice, float $maxPrice)
    {
        return $this->productRepository->findByPriceRange($minPrice, $maxPrice);
    }

    /**
     * Collection of records matching field value
     */
    public function findByField(string $field, mixed $value)
    {
        return $this->productRepository->findByField($field, $value);
    }

    /**
     * bool
     */
    public function exists(int $id)
    {
        return $this->productRepository->exists($id);
    }

    /**
     * int
     */
    public function count()
    {
        return $this->productRepository->count();
    }

    /**
     * Get paginated products
     */
    public function getPaginatedProducts($start, $count, $filter, $sortBy, $descending, $selected)
    {
        $query = \App\Models\Product::query();

        if ($filter) {
            if ($filter['field'] == "title") {
                $query->where('title', 'like', "%{$filter['value']}%");
            } else if ($filter['field'] == "code") {
                $query->where('code', 'like', "%{$filter['value']}%");
            } else if ($filter['field'] == "description") {
                $query->where('description', 'like', "%{$filter['value']}%");
            } else if ($filter['field'] == "price") {
                $query->where('price', '=', $filter['value']);
            } else if ($filter['field'] == "status") {
                $query->where('status', '=', $filter['value']);
            }
        }

        if ($selected) {
            if ($selected['field'] == "deleted") {
                $query->where('deleted_at', '!=', null);
            } else if ($selected['field'] == "featured") {
                $query->where('is_featured', true);
            } else if ($selected['field'] == "out_of_stock") {
                $query->where('stock', '<=', 0);
            }
        }

        $sortDirection = $descending ? 'desc' : 'asc';
        $query->with(['categories'])->withTrashed()->orderBy($sortBy, $sortDirection);

        $total = $query->count();
        $data = $query->skip($start)->take($count)->get();

        return [
            'data' => $data,
            'total' => $total,
        ];
    }


}
