<?php

namespace App\Modules\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Agenda\Models\Appointment;
use App\Modules\Facturation\Models\Invoice;
use App\Modules\Paiements\Models\Payment;
use App\Modules\Patients\Models\Patient;
use App\Modules\Stock\Models\Product;

/**
 * Dashboard statistics — uses database aggregates for optimized queries.
 */
class DashboardController extends Controller
{
    public function stats()
    {
        $today = now()->toDateString();

        return response()->json([
            'data' => [
                'revenue_today' => (float) Payment::whereDate('paid_at', $today)->sum('amount'),
                'active_patients' => Patient::where('status', 'actif')->count(),
                'new_patients_this_month' => Patient::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
                'appointments_today' => Appointment::whereDate('date', $today)->count(),
                'pending_appointments' => Appointment::where('status', 'en attente')->count(),
                'overdue_invoices' => Invoice::where('status', 'en retard')->count(),
                'pending_amount' => (float) Invoice::whereIn('status', ['en attente', 'partielle', 'en retard'])
                    ->selectRaw('COALESCE(SUM(total - paid), 0) as amount')
                    ->value('amount'),
                'low_stock_products' => Product::whereColumn('stock', '<', 'min_stock')->count(),
            ],
        ]);
    }
}
