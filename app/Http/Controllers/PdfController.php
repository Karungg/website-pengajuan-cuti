<?php

namespace App\Http\Controllers;

use App\Models\Request as ModelsRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PdfController extends Controller
{
    public function index(string $id)
    {
        $request = ModelsRequest::query()
            ->with(['user.position'])
            ->where('id', $id)
            ->firstOrFail();

        $headOfDivisionId = DB::table('request_details')
            ->where('request_id', $id)
            ->oldest('created_at')
            ->first(['approve_by']);

        $headOfDivisionName = DB::table('users')
            ->where('id', $headOfDivisionId->approve_by)
            ->value('name');

        $startDate = Carbon::parse($request->start_date)->addDays(-1);
        $endDate = Carbon::parse($request->end_date);
        $leaveAmount = $startDate->diffInDays($endDate);

        $pdf = Pdf::loadView('pdf', [
            'request' => $request,
            'leaveAmount' => $leaveAmount,
            'headOfDivisionName' => $headOfDivisionName
        ]);

        return $pdf->stream('formulir-cuti-tahunan-' . $request->user->name . '.pdf');
    }
}
