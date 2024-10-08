<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class FileDownloadController extends Controller
{
    public function downloadFile($path)
    {
        $filePath = 'public/' . $path; 

        if (Storage::exists($filePath)) {
            return Storage::download($filePath);
        }
    }
}
