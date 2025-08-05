<?php

namespace App\Http\Controllers;

use App\Services\ComboService;
use Illuminate\Http\Request;

class ComboController extends BaseController
{
    protected $comboService;

    public function __construct(ComboService $comboService)
    {
        $this->comboService = $comboService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $combos = $this->comboService->getAllCombos();
            return $this->sendResponse($combos, 'Combos retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving combos', [$e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $combo = $this->comboService->createCombo($request->all());
            return $this->sendResponse($combo, 'Combo created successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error creating combo', [$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $combo = $this->comboService->getComboById($id);
            if (!$combo) {
                return $this->sendError('Combo not found');
            }
            return $this->sendResponse($combo, 'Combo retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving combo', [$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $combo = $this->comboService->updateCombo($id, $request->all());
            if (!$combo) {
                return $this->sendError('Combo not found');
            }
            return $this->sendResponse($combo, 'Combo updated successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error updating combo', [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $result = $this->comboService->deleteCombo($id);
            if (!$result) {
                return $this->sendError('Combo not found');
            }
            return $this->sendResponse([], 'Combo deleted successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error deleting combo', [$e->getMessage()]);
        }
    }

    public function getActiveCombos()
    {
        try {
            $combos = $this->comboService->getActiveCombos();
            return $this->sendResponse($combos, 'Active combos retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving active combos', [$e->getMessage()]);
        }
    }
}
