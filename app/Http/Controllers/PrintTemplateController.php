<?php

namespace App\Http\Controllers;

use App\Services\PrintTemplateService;
use Illuminate\Http\Request;

class PrintTemplateController extends BaseController
{
    protected $printTemplateService;

    public function __construct(PrintTemplateService $printTemplateService)
    {
        $this->printTemplateService = $printTemplateService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $printTemplates = $this->printTemplateService->getAll();
            return $this->sendResponse($printTemplates, 'Print templates retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving print templates', [$e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $printTemplate = $this->printTemplateService->create($request->all());
            return $this->sendResponse($printTemplate, 'Print template created successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error creating print template', [$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $printTemplate = $this->printTemplateService->findById($id);
            if (!$printTemplate) {
                return $this->sendError('Print template not found');
            }
            return $this->sendResponse($printTemplate, 'Print template retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving print template', [$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $printTemplate = $this->printTemplateService->update($id, $request->all());
            if (!$printTemplate) {
                return $this->sendError('Print template not found');
            }
            return $this->sendResponse($printTemplate, 'Print template updated successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error updating print template', [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $result = $this->printTemplateService->delete($id);
            if (!$result) {
                return $this->sendError('Print template not found');
            }
            return $this->sendResponse([], 'Print template deleted successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error deleting print template', [$e->getMessage()]);
        }
    }

    /**
     * Get active print templates.
     */
    public function getActiveTemplates()
    {
        try {
            $printTemplates = $this->printTemplateService->getActiveTemplates();
            return $this->sendResponse($printTemplates, 'Active print templates retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving active print templates', [$e->getMessage()]);
        }
    }
}
