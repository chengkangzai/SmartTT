<?php

namespace App\Http\Controllers;

use App\Actions\Reports\Export\ExportSalesReportAction;
use App\Actions\Reports\ExportReportInterface;
use App\Actions\Reports\ViewBag\GetViewBagForSalesReportAction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(string $mode)
    {
        abort_if(!auth()->user()->can('Access Report'), 403);
        abort_if($mode !== 'sales', 404);
        $action = match ($mode) {
            'sales' => app(GetViewBagForSalesReportAction::class),
        };

        $data = $action->execute();
        return view('smartTT.report.sales', $data);
    }

    public function export(Request $request, string $mode)
    {
        abort_if(!auth()->user()->can('Access Report'), 403);
        abort_if($mode !== 'sales', 404);

        /** @var ExportReportInterface $action */
        $action = match ($mode) {
            'sales' => app(ExportSalesReportAction::class),
        };

        $action->execute($request->all());

        return null;
    }
}
