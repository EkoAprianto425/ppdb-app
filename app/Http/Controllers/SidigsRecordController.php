<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SidigsRecord;
use App\Services\SidigsService;
use Illuminate\Http\Request;

class SidigsRecordController extends Controller
{
    public function index()
    {
        $records = SidigsRecord::with('registration.user')->latest()->paginate(15);
        return view('admin.sidigs.index', compact('records'));
    }

    public function repost(SidigsRecord $record)
    {
        if ($record->status === 'success') {
            return back()->with('error', 'Data sudah berhasil dikirim ke SIDIGS.');
        }

        $registration = $record->registration()->with('user.educationalLevel')->first();
        $record->delete();

        $success = SidigsService::postStudent($registration);

        return back()->with(
            $success ? 'success' : 'error',
            $success ? 'Berhasil dikirim ulang ke SIDIGS.' : 'Gagal dikirim ulang ke SIDIGS. Cek response terbaru.'
        );
    }
}
