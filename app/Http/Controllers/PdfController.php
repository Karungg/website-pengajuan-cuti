<?php

namespace App\Http\Controllers;

use App\Models\Request as ModelsRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function index(string $id)
    {
        $request = ModelsRequest::query()
            ->with(['user.position'])
            ->where('id', $id)
            ->firstOrFail();

        $startDate = Carbon::parse($request->start_date)->addDays(-1);
        $endDate = Carbon::parse($request->end_date);
        $leaveAmount = $startDate->diffInDays($endDate);

        $pdf = Pdf::loadView('pdf', [
            'request' => $request,
            'leaveAmount' => $leaveAmount
        ]);

        return $pdf->stream('formulir-cuti-tahunan-' . $request->user->name . '.pdf');
    }
}
