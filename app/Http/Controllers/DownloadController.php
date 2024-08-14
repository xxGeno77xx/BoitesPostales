<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DownloadController extends Controller
{
    public function downloadPdf($record)
    {

        return Pdf::loadView('contrat', ['record' => $record])
            ->stream('Contrat.pdf');
    }
}
