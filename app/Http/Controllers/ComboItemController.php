<?php

namespace App\Http\Controllers;

use App\Services\ComboItemService;
use Illuminate\Http\Request;

class ComboItemController extends BaseController
{
    protected $comboItemService;

    public function __construct(ComboItemService $comboItemService)
    {
        $this->comboItemService = $comboItemService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $comboItems = $this->comboItemService->getAllComboItems();
            return $this->sendResponse($comboItems, 'Combo items retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving combo items', [$e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $comboItem = $this->comboItemService->createComboItem($request->all());
            return $this->sendResponse($comboItem, 'Combo item created successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error creating combo item', [$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $comboItem = $this->comboItemService->getComboItemById($id);
            if (!$comboItem) {
                return $this->sendError('Combo item not found');
            }
            return $this->sendResponse($comboItem, 'Combo item retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving combo item', [$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $comboItem = $this->comboItemService->updateComboItem($id, $request->all());
            if (!$comboItem) {
                return $this->sendError('Combo item not found');
            }
            return $this->sendResponse($comboItem, 'Combo item updated successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error updating combo item', [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $result = $this->comboItemService->deleteComboItem($id);
            if (!$result) {
                return $this->sendError('Combo item not found');
            }
            return $this->sendResponse([], 'Combo item deleted successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error deleting combo item', [$e->getMessage()]);
        }
    }

    public function getByComboId($comboId)
    {
        try {
            $comboItems = $this->comboItemService->getByComboId($comboId);
            return $this->sendResponse($comboItems, 'Combo items retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving combo items', [$e->getMessage()]);
        }
    }
}
