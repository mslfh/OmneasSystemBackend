<?php

namespace App\Http\Controllers;

use App\Services\PrintLogService;
use Illuminate\Http\Request;

class PrintLogController extends BaseController
{
    protected $printLogService;

    public function __construct(PrintLogService $printLogService)
    {
        $this->printLogService = $printLogService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $printLogs = $this->printLogService->getAllPrintLogs();
            return $this->sendResponse($printLogs, 'Print logs retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving print logs', [$e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $printLog = $this->printLogService->createPrintLog($request->all());
            return $this->sendResponse($printLog, 'Print log created successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error creating print log', [$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $printLog = $this->printLogService->getPrintLogById($id);
            if (!$printLog) {
                return $this->sendError('Print log not found');
            }
            return $this->sendResponse($printLog, 'Print log retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving print log', [$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $printLog = $this->printLogService->updatePrintLog($id, $request->all());
            if (!$printLog) {
                return $this->sendError('Print log not found');
            }
            return $this->sendResponse($printLog, 'Print log updated successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error updating print log', [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $result = $this->printLogService->deletePrintLog($id);
            if (!$result) {
                return $this->sendError('Print log not found');
            }
            return $this->sendResponse([], 'Print log deleted successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error deleting print log', [$e->getMessage()]);
        }
    }

    /**
     * Get print logs by printer ID.
     */
    public function getByPrinterId($printerId)
    {
        try {
            $printLogs = $this->printLogService->getByPrinterId($printerId);
            return $this->sendResponse($printLogs, 'Print logs retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving print logs', [$e->getMessage()]);
        }
    }
}
