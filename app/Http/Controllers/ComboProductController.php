<?php

namespace App\Http\Controllers;

use App\Services\ComboProductService;
use Illuminate\Http\Request;

class ComboProductController extends BaseController
{
    protected $comboProductService;

    public function __construct(ComboProductService $comboProductService)
    {
        $this->comboProductService = $comboProductService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $comboProducts = $this->comboProductService->getAllComboProducts();
            return $this->sendResponse($comboProducts, 'Combo products retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving combo products', [$e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $comboProduct = $this->comboProductService->createComboProduct($request->all());
            return $this->sendResponse($comboProduct, 'Combo product created successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error creating combo product', [$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $comboProduct = $this->comboProductService->getComboProductById($id);
            if (!$comboProduct) {
                return $this->sendError('Combo product not found');
            }
            return $this->sendResponse($comboProduct, 'Combo product retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving combo product', [$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $comboProduct = $this->comboProductService->updateComboProduct($id, $request->all());
            if (!$comboProduct) {
                return $this->sendError('Combo product not found');
            }
            return $this->sendResponse($comboProduct, 'Combo product updated successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error updating combo product', [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $result = $this->comboProductService->deleteComboProduct($id);
            if (!$result) {
                return $this->sendError('Combo product not found');
            }
            return $this->sendResponse([], 'Combo product deleted successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error deleting combo product', [$e->getMessage()]);
        }
    }

    public function getByComboId($comboId)
    {
        try {
            $comboProducts = $this->comboProductService->getByComboId($comboId);
            return $this->sendResponse($comboProducts, 'Combo products retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving combo products', [$e->getMessage()]);
        }
    }
}
