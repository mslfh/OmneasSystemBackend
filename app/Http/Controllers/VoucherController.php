<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Http\Controllers\Controller;
use App\Services\VoucherService;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    protected $voucherService;

    public function __construct(VoucherService $voucherService)
    {
        $this->voucherService = $voucherService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $start = $request->query('start', 0);
        $count = $request->query('count', 10);
        $filter = $request->query('filter', null);
        $sortBy = $request->query('sortBy', 'id');
        $descending = $request->query('descending', false);
        $vouchers = $this->voucherService->getPaginatedVouchers($start, $count, $filter, $sortBy, $descending);
        return response()->json([
            'rows' => $vouchers['data'],
            'total' => $vouchers['total'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $voucher = $this->voucherService->createVoucher($data);
        return response()->json($voucher, 201);
    }

    public function bulkStore(Request $request)
    {
        $data = $request->all();
        $vouchers = $this->voucherService->bulkCreateVoucher($data);
        return response()->json($vouchers, 201);
    }




    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return response()->json($this->voucherService->getVoucherById($id));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Voucher $voucher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $voucher = $this->voucherService->updateVoucher($id, $data);
        return response()->json($voucher);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->voucherService->deleteVoucher($id);
        return response()->json(null, 204);
    }

    /**
     * Find a voucher by its code.
     */
    public function findByCode($code)
    {
        $voucher = $this->voucherService->findByCode($code);
        if ($voucher) {
            return response()->json($voucher);
        }
        return response()->json(['message' => 'Voucher not found'], 404);
    }

    /**
     * Verify voucher.
     */
    public function verify(Request $request)
    {
        $data = $request->all();
        $result = $this->voucherService->verifyVoucher($data['code'] );
        return response()->json($result);
    }

    /**
     * Verify codes if a valid voucher.
     */
    public function verifyValidCode(Request $request)
    {
        $data = $request->validate([
            'vouchers' => 'required|array',
            'vouchers.*' => 'string'
        ]);
        $result = $this->voucherService->verifyValidCode($data['vouchers']);
        return response()->json($result);
    }
}
