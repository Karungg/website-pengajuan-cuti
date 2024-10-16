<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnountcementController extends Controller
{
    public function index(string $id): View
    {
        return view('annoutcement');
    }
}
