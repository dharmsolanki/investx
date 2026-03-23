<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('user', 'investment.plan')->latest();

        // Filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('payment_id', 'like', "%{$s}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$s}%")
                      ->orWhere('email', 'like', "%{$s}%")
                      ->orWhere('phone', 'like', "%{$s}%"));
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Stats
        $stats = [
            'total_deposits'    => Transaction::where('type', 'deposit')->where('status', 'completed')->sum('amount'),
            'total_withdrawals' => Transaction::where('type', 'withdrawal')->where('status', 'completed')->sum('amount'),
            'total_profit'      => Transaction::where('type', 'profit')->where('status', 'completed')->sum('amount'),
            'total_commission'  => Transaction::where('type', 'commission')->where('status', 'completed')->sum('amount'),
            'today_deposits'    => Transaction::where('type', 'deposit')->where('status', 'completed')->whereDate('created_at', today())->sum('amount'),
            'today_withdrawals' => Transaction::where('type', 'withdrawal')->where('status', 'completed')->whereDate('created_at', today())->sum('amount'),
            'pending_count'     => Transaction::where('status', 'pending')->count(),
        ];

        // CSV Export
        if ($request->filled('export')) {
            return $this->exportCsv($query->get());
        }

        $transactions = $query->paginate(25)->withQueryString();

        return view('admin.transactions.index', compact('transactions', 'stats'));
    }

    private function exportCsv($transactions)
    {
        $filename = 'transactions_' . now()->format('Y_m_d_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, ['ID', 'User', 'Email', 'Phone', 'Type', 'Amount', 'Payment Method', 'Payment ID', 'Status', 'Notes', 'Date']);

            foreach ($transactions as $txn) {
                fputcsv($file, [
                    $txn->id,
                    $txn->user->name ?? '—',
                    $txn->user->email ?? '—',
                    $txn->user->phone ?? '—',
                    ucfirst($txn->type),
                    $txn->amount,
                    $txn->payment_method ?? '—',
                    $txn->payment_id ?? '—',
                    ucfirst($txn->status),
                    $txn->notes ?? '—',
                    $txn->created_at->format('d M Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}