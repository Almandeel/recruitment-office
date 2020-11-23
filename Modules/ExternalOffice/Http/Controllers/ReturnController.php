<?php

namespace Modules\ExternalOffice\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Main\Models\Office;
use Modules\Accounting\Models\Voucher;
use Modules\ExternalOffice\Models\{Country, Profession, Cv, Advance, Returned};

class ReturnController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission:return-create')->only(['create', 'store']);
        $this->middleware('permission:return-read')->only(['index', 'show']);
        $this->middleware('permission:return-update')->only(['edit', 'update']);
        $this->middleware('permission:return-delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $from_date = $request->from_date ? $request->from_date : now();
        $to_date = $request->to_date ? $request->to_date : now();

        $type = $request->has('type') ? $request->type : 'all';

        $returns = office()->returns->sortByDesc('created_at');
        if ($type != 'all') {
            if ($type == 'payed') {
                $returns = $returns->whereNotNull('advance_id');
            } else if ($type == 'free') {
                $returns = $returns->whereNull('advance_id');
            }
        }
        $returns = $returns->whereBetween('created_at', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        return view('externaloffice::returns.index', compact('type', 'returns', 'from_date', 'to_date'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show(Returned $return)
    {
        $crumbs = [
            [route('offices.returns.index'), 'Returns'],
            ['#', 'Returned cv: ' . $return->cv->id]
        ];
        $advance = $return->advance;
        return view('externaloffice::returns.show', compact('return', 'advance', 'crumbs'));
    }
}
