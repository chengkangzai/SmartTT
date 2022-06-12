<?php

namespace App\Actions\Reports;

use App\Models\Booking;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ExportSalesReportAction implements ExportReportInterface
{
    public function execute(array $data)
    {
        $booking = Booking::query()
            ->with(['payment', 'package', 'package.tour'])
            ->when($data['category'] != '', function ($query) use ($data) {
                $query->whereHas('package.tour', function ($query) use ($data) {
                    $query->where('category', $data['category']);
                });
            })
            ->when($data['start_date'], function ($query) use ($data) {
                $query->where('bookings.created_at', '>=', $data['start_date']);
            })
            ->when($data['end_date'], function ($query) use ($data) {
                $query->where('bookings.created_at', '<=', $data['end_date']);
            })
            ->get();

        $writer = SimpleExcelWriter::streamDownload('Sales Report-' . now()->format('Y-m-d H:i:s') . '.xlsx');

        $writer->addRow(['Booking ID', 'Depart time', 'Tour', 'Made By', 'Payment Status', 'Amount', 'Created At']);

        foreach ($booking as $item) {
            $writer->addRow([
                $item->id,
                $item->package->depart_time->format('Y-m-d H:i:s'),
                $item->package->tour->name,
                $item->user->name,
                $item->isFullPaid() ? __('Full Paid') : __('Partial Paid'),
                number_format($item->total_price, 2),
                $item->created_at->format('Y-m-d H:i:s'),
            ]);
        }

        $writer->addRow(['', '', '', '','Total', number_format($booking->sum('total_price'),2)]);

        $writer->toBrowser();
        $writer->close();

    }
}
