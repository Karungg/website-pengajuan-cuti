<?php

namespace App\Http\Controllers;

use App\Models\Request as ModelsRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnnountcementController extends Controller
{
    public function index(string $id): View
    {
        $request = ModelsRequest::query()
            ->findOrFail($id, ['id']);

        $requestLog = DB::table('request_logs')
            ->where('request_id', $id)
            ->orderByDesc('created_at')
            ->limit(3)
            ->get([
                'id',
                'created_at',
                'user_id'
            ]);

        $approvedBy = User::query()
            ->findOrFail($requestLog[0]->user_id, ['name', 'signature']);

        return view('annountcement', [
            'request' => $request,
            'requestLog' => $requestLog,
            'approvedBy' => $approvedBy
        ]);
    }
}
