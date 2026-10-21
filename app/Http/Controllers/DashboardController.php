<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CommercialInvoice;
use App\Models\Product;
use App\Models\ShippingCompany;
use App\Models\ShippingFee;
use App\Models\Supplier;
use App\Models\SupplierPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        [$from, $to, $range] = $this->resolveRange($request->query('range', 'this_month'));

        // -------------------------------------------------
        // KPI CARDS
        // -------------------------------------------------

        $totalClients = Client::count();

        $totalSuppliers = Supplier::count();
        $totalShops = Supplier::where('type', 'shop')->count();
        $totalFactories = Supplier::where('type', 'factory')->count();

        $totalProducts = Product::count();

        $invoicesThisMonth = CommercialInvoice::whereBetween('date', [$from, $to])->count();
        $invoicesThisMonthTotal = CommercialInvoice::whereBetween('date', [$from, $to])
            ->join('commercial_invoice_product', 'commercial_invoices.id', '=', 'commercial_invoice_product.commercial_invoice_id')
            ->join('products', 'commercial_invoice_product.product_id', '=', 'products.id')
            ->selectRaw('SUM(commercial_invoice_product.ctn * products.pcs_cts * commercial_invoice_product.unit_price) as total')
            ->value('total') ?? 0;

        $shippingFeesRmb = ShippingFee::whereBetween('date', [$from, $to])
            ->where('currency', 'RMB')->sum('amount');
        $shippingFeesUsd = ShippingFee::whereBetween('date', [$from, $to])
            ->where('currency', 'USD')->sum('amount');

        $outstandingAmount = SupplierPurchase::whereIn('payment_status', ['unpaid', 'partial'])
            ->sum('total_amount');

        // -------------------------------------------------
        // CHART: MONTHLY SALES TREND (last 12 months)
        // -------------------------------------------------

        $salesRows = DB::table('commercial_invoices')
            ->join('commercial_invoice_product', 'commercial_invoices.id', '=', 'commercial_invoice_product.commercial_invoice_id')
            ->join('products', 'commercial_invoice_product.product_id', '=', 'products.id')
            ->whereNotNull('commercial_invoices.date')
            ->where('commercial_invoices.date', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->selectRaw("DATE_FORMAT(commercial_invoices.date, '%Y-%m') as ym")
            ->selectRaw('SUM(commercial_invoice_product.ctn * products.pcs_cts * commercial_invoice_product.unit_price) as total')
            ->groupBy('ym')
            ->orderBy('ym')
            ->get()
            ->keyBy('ym');

        $salesLabels = [];
        $salesValues = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i);
            $key = $m->format('Y-m');
            $salesLabels[] = $m->format('M Y');
            $salesValues[] = round((float) ($salesRows[$key]->total ?? 0), 2);
        }

        // -------------------------------------------------
        // CHART: SUPPLIER TYPE SPLIT
        // -------------------------------------------------

        $supplierTypeLabels = ['Shops', 'Factories'];
        $supplierTypeValues = [$totalShops, $totalFactories];

        // -------------------------------------------------
        // CHART: TOP 5 CLIENTS BY INVOICE VALUE
        // -------------------------------------------------

        $topClients = DB::table('commercial_invoices')
            ->join('clients', 'commercial_invoices.client_id', '=', 'clients.id')
            ->join('commercial_invoice_product', 'commercial_invoices.id', '=', 'commercial_invoice_product.commercial_invoice_id')
            ->join('products', 'commercial_invoice_product.product_id', '=', 'products.id')
            ->selectRaw('clients.id as client_id, clients.name as client_name')
            ->selectRaw('SUM(commercial_invoice_product.ctn * products.pcs_cts * commercial_invoice_product.unit_price) as total')
            ->groupBy('clients.id', 'clients.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topClientLabels = $topClients->pluck('client_name')->all();
        $topClientValues = $topClients->pluck('total')->map(fn ($v) => round((float) $v, 2))->all();

        // -------------------------------------------------
        // CHART: TOP 5 SUPPLIERS BY PURCHASE VALUE
        // -------------------------------------------------

        $topSuppliers = SupplierPurchase::select('supplier_id')
            ->selectRaw('SUM(total_amount) as total')
            ->with('supplier:id,name')
            ->groupBy('supplier_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topSupplierLabels = $topSuppliers->map(fn ($r) => $r->supplier?->name ?? '—')->all();
        $topSupplierValues = $topSuppliers->map(fn ($r) => round((float) $r->total, 2))->all();

        // -------------------------------------------------
        // RECENT LISTS
        // -------------------------------------------------

        $recentInvoices = CommercialInvoice::with('client')
            ->latest('created_at')
            ->limit(5)
            ->get();

        $recentFees = ShippingFee::with(['shippingCompany', 'commercialInvoice'])
            ->latest('date')
            ->limit(5)
            ->get();

        // -------------------------------------------------
        // ALERTS / PENDING ITEMS
        // -------------------------------------------------

        $alerts = [];

        $unpaidCount = SupplierPurchase::where('payment_status', 'unpaid')->count();
        if ($unpaidCount > 0) {
            $alerts[] = [
                'type' => 'warning',
                'text' => "$unpaidCount supplier purchase(s) are unpaid.",
            ];
        }

        $partialCount = SupplierPurchase::where('payment_status', 'partial')->count();
        if ($partialCount > 0) {
            $alerts[] = [
                'type' => 'info',
                'text' => "$partialCount supplier purchase(s) are partially paid.",
            ];
        }

        $neverShipped = Product::whereDoesntHave('commercialInvoices')->count();
        if ($neverShipped > 0) {
            $alerts[] = [
                'type' => 'info',
                'text' => "$neverShipped product(s) have never been shipped in a commercial invoice.",
            ];
        }

        $companiesWithoutFees = ShippingCompany::doesntHave('fees')->count();
        if ($companiesWithoutFees > 0) {
            $alerts[] = [
                'type' => 'info',
                'text' => "$companiesWithoutFees shipping company(ies) have no fees recorded.",
            ];
        }

        return view('dashboard', compact(
            'range',
            'from',
            'to',
            'totalClients',
            'totalSuppliers',
            'totalShops',
            'totalFactories',
            'totalProducts',
            'invoicesThisMonth',
            'invoicesThisMonthTotal',
            'shippingFeesRmb',
            'shippingFeesUsd',
            'outstandingAmount',
            'salesLabels',
            'salesValues',
            'supplierTypeLabels',
            'supplierTypeValues',
            'topClientLabels',
            'topClientValues',
            'topSupplierLabels',
            'topSupplierValues',
            'recentInvoices',
            'recentFees',
            'alerts'
        ));
    }

    private function resolveRange(string $range): array
    {
        $now = Carbon::now();

        switch ($range) {
            case 'last_month':
                $from = $now->copy()->subMonth()->startOfMonth();
                $to = $now->copy()->subMonth()->endOfMonth();
                break;

            case 'last_3_months':
                $from = $now->copy()->subMonths(2)->startOfMonth();
                $to = $now->copy()->endOfMonth();
                break;

            case 'this_year':
                $from = $now->copy()->startOfYear();
                $to = $now->copy()->endOfYear();
                break;

            case 'this_month':
            default:
                $range = 'this_month';
                $from = $now->copy()->startOfMonth();
                $to = $now->copy()->endOfMonth();
                break;
        }

        return [$from, $to, $range];
    }
}