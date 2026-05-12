<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SalesReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class SalesReportController extends Controller
{
    public function showReportForm()
    {
        $sellers = User::orderBy('name')->get();

        return view('sales.report', compact('sellers'));
    }

    public function generateReport(Request $request, SalesReportService $reportService)
    {
        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'format' => 'required|in:text,csv,pdf',
                'seller_id' => 'nullable|exists:users,id',
            ]);

            $startDate = Carbon::parse($request->start_date)->format('Y-m-d');
            $endDate = Carbon::parse($request->end_date)->format('Y-m-d');

            $options = [];

            if ($request->filled('seller_id')) {
                $options['seller_id'] = $request->seller_id;
            }

            $report = $reportService->generateSalesReport($startDate, $endDate, $options);

            if ($request->input('format') === 'csv') {
                $content = $reportService->exportToCSV($report);
                $filename = 'reporte_ventas_' . date('Y-m-d_His') . '.csv';

                return response($content)
                    ->header('Content-Type', 'text/csv; charset=utf-8')
                    ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                    ->header('Content-Transfer-Encoding', 'binary');
            }

            if ($request->input('format') === 'pdf') {
                $pdf = PDF::loadView('sales.report-pdf', compact('report'))
                    ->setPaper('a4', 'landscape')
                    ->setOption('margin-top', 10)
                    ->setOption('margin-bottom', 10)
                    ->setOption('margin-left', 10)
                    ->setOption('margin-right', 10);

                $filename = 'reporte_ventas_' . date('Y-m-d_His') . '.pdf';

                return $pdf->download($filename);
            }

            $reportParams = [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'seller_id' => $request->seller_id,
            ];

            return view('sales.report-view', compact('report', 'reportParams'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al generar el reporte: ' . $e->getMessage())
                ->withInput();
        }
    }
}
