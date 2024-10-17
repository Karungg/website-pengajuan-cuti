<?php

namespace App\Http\Controllers;

use App\Models\Request as ModelsRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnountcementController extends Controller
{
    public function index(string $id): View
    {
        $request = ModelsRequest::query()
            ->findOrFail($id);

        return view('annoutcement', [
            'request' => $request
        ]);
    }
}
