<?php

namespace App\Http\Controllers;

use App\Services\PrinterService;
use Illuminate\Http\Request;

class PrinterController extends BaseController
{
    protected $printerService;

    public function __construct(PrinterService $printerService)
    {
        $this->printerService = $printerService;
    }

    public function index()
    {
        try {
            $printers = $this->printerService->getAllPrinters();
            return $this->sendResponse($printers, 'Printers retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving printers', [$e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $printer = $this->printerService->createPrinter($request->all());
            return $this->sendResponse($printer, 'Printer created successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error creating printer', [$e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $printer = $this->printerService->getPrinterById($id);
            if (!$printer) {
                return $this->sendError('Printer not found');
            }
            return $this->sendResponse($printer, 'Printer retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving printer', [$e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $printer = $this->printerService->updatePrinter($id, $request->all());
            if (!$printer) {
                return $this->sendError('Printer not found');
            }
            return $this->sendResponse($printer, 'Printer updated successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error updating printer', [$e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $result = $this->printerService->deletePrinter($id);
            if (!$result) {
                return $this->sendError('Printer not found');
            }
            return $this->sendResponse([], 'Printer deleted successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error deleting printer', [$e->getMessage()]);
        }
    }

    public function getActivePrinters()
    {
        try {
            $printers = $this->printerService->getActivePrinters();
            return $this->sendResponse($printers, 'Active printers retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving active printers', [$e->getMessage()]);
        }
    }
}
