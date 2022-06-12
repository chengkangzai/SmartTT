<?php

namespace App\Http\Controllers;

use App\Actions\Reports\ExportReportInterface;
use App\Actions\Reports\ExportSalesReportAction;
use App\Models\Tour;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct()
    {
        abort_if(auth()->user()->cannot('Access Report'), 403);
    }

    public function index(string $mode)
    {
        $data = [
            'categories' => Tour::select('category')
                ->distinct()
                ->pluck('category')
        ];
        return view('smartTT.report.sales', $data);
    }

    public function export(Request $request, string $mode)
    {
        abort_if($mode !== 'sales', 404);

        /** @var ExportReportInterface $action */
        $action = match ($mode) {
            'sales' => app(ExportSalesReportAction::class),
        };

        $action->execute($request->all());

        return null;
    }
}
