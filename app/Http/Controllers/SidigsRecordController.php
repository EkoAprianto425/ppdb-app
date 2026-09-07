<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SidigsRecordController extends Controller
{
    public function index()
    {
        $records = \App\Models\SidigsRecord::with('registration.user')->latest()->paginate(15);
        return view('admin.sidigs.index', compact('records'));
    }
}
