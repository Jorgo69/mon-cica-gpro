<?php

namespace App\Http\Controllers\V1\PDF;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\PDF\GenerateProjectReportAction;

class ProjectExportController extends Controller
{
    public function exportPdf(string $id, Request $request)
    {
        try {
            $action = app(GenerateProjectReportAction::class);
            $templateKey = $request->query('template', 'classic');

            $path = $action->execute($id, $templateKey);

            return response()->download($path)->deleteFileAfterSend(false);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la generation du PDF : ' . $e->getMessage());
        }
    }
}
