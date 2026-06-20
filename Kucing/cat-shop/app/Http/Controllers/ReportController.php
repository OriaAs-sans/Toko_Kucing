<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');

        $query = Order::query();
        if ($from) $query->whereDate('created_at', '>=', $from);
        if ($to) $query->whereDate('created_at', '<=', $to);

        $orders = $query->get();

        $totalRevenue = $orders->sum('total_amount');
        $totalCost = $orders->sum('total_cost');
        $totalFees = $orders->sum('marketplace_fee');
        $totalDiscounts = $orders->sum('discount_amount');
        $profit = $orders->sum('profit');

        return view('reports.index', compact('orders','totalRevenue','totalCost','totalFees','totalDiscounts','profit','from','to'));
    }
}
