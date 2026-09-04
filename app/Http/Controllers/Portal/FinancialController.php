<?php
declare(strict_types=1);

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinancialController extends BaseController
{
    private function checkPermission(): void
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        $hasAccess = DB::table('committee_members')
            ->where('user_id', $user->id)
            ->where('is_active', 1)
            ->where('can_manage_finance', 1)
            ->whereNull('deleted_at')
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Financial management is restricted exclusively to committee members granted financial access by Admin.');
        }
    }

    public function index(Request $request)
    {
        $this->checkPermission();

        $totalFunds = (float) DB::table('association_funds')->sum('amount');
        $totalExpenses = (float) DB::table('association_expenses')->sum('amount');
        $balance = $totalFunds - $totalExpenses;

        $currentYear = date('Y') . '-' . (date('Y') + 1);
        $selectedYear = trim((string)$request->input('fiscal_year', $currentYear));

        $budgets = DB::table('yearly_budgets')->where('fiscal_year', $selectedYear)->orderBy('category', 'asc')->get()->map(fn($r) => (array)$r)->toArray();
        $totalAllocatedBudget = array_sum(array_column($budgets, 'allocated_amount'));

        $recentFunds = DB::table('association_funds')->orderBy('fund_date', 'desc')->limit(5)->get()->map(fn($r) => (array)$r)->toArray();
        $recentExpenses = DB::table('association_expenses')->orderBy('expense_date', 'desc')->limit(5)->get()->map(fn($r) => (array)$r)->toArray();

        return $this->legacyView(
            'portal/financials/index',
            compact('totalFunds', 'totalExpenses', 'balance', 'selectedYear', 'budgets', 'totalAllocatedBudget', 'recentFunds', 'recentExpenses'),
            'portal',
            'Financial Overview'
        );
    }

    public function funds(Request $request)
    {
        $this->checkPermission();

        $totalFunds = (float) DB::table('association_funds')->sum('amount');
        $membershipFunds = (float) DB::table('association_funds')->where('source', 'Membership Collection')->sum('amount');
        $otherFunds = $totalFunds - $membershipFunds;

        $funds = DB::table('association_funds')->orderBy('fund_date', 'desc')->get()->map(fn($r) => (array)$r)->toArray();

        return $this->legacyView(
            'portal/financials/funds',
            compact('totalFunds', 'membershipFunds', 'otherFunds', 'funds'),
            'portal',
            'Association Funds'
        );
    }

    public function storeFund(Request $request)
    {
        $this->checkPermission();
        $user = Auth::user();

        $title        = trim((string)$request->input('title', ''));
        $source       = trim((string)$request->input('source', 'General Fund'));
        $amount       = (float)$request->input('amount', 0);
        $fund_date    = trim((string)$request->input('fund_date', date('Y-m-d')));
        $reference_no = trim((string)$request->input('reference_no', ''));
        $notes        = trim((string)$request->input('notes', ''));

        if (empty($title) || $amount <= 0) {
            return redirect('/portal/financials/funds')->with('error', 'তহবিলের বিবরণ ও টাকার পরিমাণ সঠিক হওয়া আবশ্যক।')->withInput();
        }

        DB::table('association_funds')->insert([
            'title'        => $title,
            'source'       => $source,
            'amount'       => $amount,
            'fund_date'    => $fund_date,
            'reference_no' => $reference_no,
            'notes'        => $notes,
            'created_by'   => $user->id,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect('/portal/financials/funds')->with('success', 'নতুন তহবিল জমা রেকর্ড করা হয়েছে।');
    }

    public function exportFundsExcel(Request $request)
    {
        $this->checkPermission();

        $funds = DB::table('association_funds')->select('fund_date', 'title', 'source', 'reference_no', 'amount', 'notes')->orderBy('fund_date', 'desc')->get()->map(fn($r) => (array)$r)->toArray();

        $headers = ['তারিখ (Date)', 'বিবরণ (Title)', 'উৎস (Source)', 'রেফারেন্স (Ref No)', 'পরিমাণ (Amount BDT)', 'নোট (Notes)'];
        return $this->exportCsv($funds, 'association_funds_' . date('Ymd'), $headers);
    }

    public function exportFundsPdf(Request $request)
    {
        $this->checkPermission();

        $funds = DB::table('association_funds')->orderBy('fund_date', 'desc')->get()->map(fn($r) => (array)$r)->toArray();
        $reportTitle = 'Association Funds Report (তহবিল জমা সংক্রান্ত প্রতিবেদন)';
        $totalAmount = array_sum(array_column($funds, 'amount'));

        extract(compact('funds', 'reportTitle', 'totalAmount'));
        $viewFile = resource_path('views/portal/financials/print_report.php');
        if (file_exists($viewFile)) {
            ob_start();
            require $viewFile;
            return response(ob_get_clean());
        }
        abort(404);
    }

    public function expenses(Request $request)
    {
        $this->checkPermission();

        $totalExpenses = (float) DB::table('association_expenses')->sum('amount');
        $expenses = DB::table('association_expenses')->orderBy('expense_date', 'desc')->get()->map(fn($r) => (array)$r)->toArray();

        return $this->legacyView(
            'portal/financials/expenses',
            compact('totalExpenses', 'expenses'),
            'portal',
            'Association Expenses'
        );
    }

    public function storeExpense(Request $request)
    {
        $this->checkPermission();
        $user = Auth::user();

        $title        = trim((string)$request->input('title', ''));
        $category     = trim((string)$request->input('category', 'General'));
        $amount       = (float)$request->input('amount', 0);
        $expense_date = trim((string)$request->input('expense_date', date('Y-m-d')));
        $voucher_no   = trim((string)$request->input('voucher_no', ''));
        $notes        = trim((string)$request->input('notes', ''));

        if (empty($title) || $amount <= 0) {
            return redirect('/portal/financials/expenses')->with('error', 'ব্যয়ের শিরোনাম ও সঠিক পরিমাণ আবশ্যক।')->withInput();
        }

        DB::table('association_expenses')->insert([
            'title'        => $title,
            'category'     => $category,
            'amount'       => $amount,
            'expense_date' => $expense_date,
            'voucher_no'   => $voucher_no,
            'notes'        => $notes,
            'created_by'   => $user->id,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect('/portal/financials/expenses')->with('success', 'নতুন খরচের হিসাব এনট্রি করা হয়েছে।');
    }

    public function exportExpensesExcel(Request $request)
    {
        $this->checkPermission();

        $expenses = DB::table('association_expenses')->select('expense_date', 'title', 'category', 'voucher_no', 'amount', 'notes')->orderBy('expense_date', 'desc')->get()->map(fn($r) => (array)$r)->toArray();

        $headers = ['তারিখ (Date)', 'ব্যয়ের বিবরণ (Title)', 'খাত (Category)', 'ভাউচার নং (Voucher)', 'পরিমাণ (Amount BDT)', 'নোট (Notes)'];
        return $this->exportCsv($expenses, 'association_expenses_' . date('Ymd'), $headers);
    }

    public function exportExpensesPdf(Request $request)
    {
        $this->checkPermission();

        $expenses = DB::table('association_expenses')->orderBy('expense_date', 'desc')->get()->map(fn($r) => (array)$r)->toArray();
        $reportTitle = 'Association Expenses Report (ব্যয় ও খরচের প্রতিবেদন)';
        $totalAmount = array_sum(array_column($expenses, 'amount'));

        extract(compact('expenses', 'reportTitle', 'totalAmount'));
        $viewFile = resource_path('views/portal/financials/print_report.php');
        if (file_exists($viewFile)) {
            ob_start();
            require $viewFile;
            return response(ob_get_clean());
        }
        abort(404);
    }

    public function budgets(Request $request)
    {
        $this->checkPermission();

        $currentYear = date('Y') . '-' . (date('Y') + 1);
        $selectedYear = trim((string)$request->input('fiscal_year', $currentYear));

        $fiscalYears = DB::table('yearly_budgets')->distinct()->orderBy('fiscal_year', 'desc')->pluck('fiscal_year')->toArray();
        if (!in_array($currentYear, $fiscalYears)) {
            array_unshift($fiscalYears, $currentYear);
        }

        $budgets = DB::table('yearly_budgets')->where('fiscal_year', $selectedYear)->orderBy('category', 'asc')->get()->map(fn($r) => (array)$r)->toArray();
        $totalAllocatedBudget = array_sum(array_column($budgets, 'allocated_amount'));

        return $this->legacyView(
            'portal/financials/budgets',
            compact('fiscalYears', 'selectedYear', 'budgets', 'totalAllocatedBudget'),
            'portal',
            'Yearly Budget Allocation'
        );
    }

    public function storeBudget(Request $request)
    {
        $this->checkPermission();
        $user = Auth::user();

        $fiscal_year      = trim((string)$request->input('fiscal_year', ''));
        $category         = trim((string)$request->input('category', ''));
        $allocated_amount = (float)$request->input('allocated_amount', 0);
        $notes            = trim((string)$request->input('notes', ''));

        if (empty($fiscal_year) || empty($category) || $allocated_amount <= 0) {
            return redirect('/portal/financials/budgets')->with('error', 'অর্থবছর, বাজেট খাত ও সঠিক বরাদ্দের পরিমাণ আবশ্যক।')->withInput();
        }

        DB::table('yearly_budgets')->insert([
            'fiscal_year'      => $fiscal_year,
            'category'         => $category,
            'allocated_amount' => $allocated_amount,
            'notes'            => $notes,
            'created_by'       => $user->id,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        return redirect('/portal/financials/budgets?fiscal_year=' . urlencode($fiscal_year))->with('success', 'বার্ষিক বাজেট বরাদ্দ সেট করা হয়েছে।');
    }

    public function exportBudgetsExcel(Request $request)
    {
        $this->checkPermission();

        $currentYear = date('Y') . '-' . (date('Y') + 1);
        $selectedYear = trim((string)$request->input('fiscal_year', $currentYear));

        $budgets = DB::table('yearly_budgets')->select('fiscal_year', 'category', 'allocated_amount', 'notes')->where('fiscal_year', $selectedYear)->orderBy('category', 'asc')->get()->map(fn($r) => (array)$r)->toArray();

        $headers = ['অর্থবছর (Fiscal Year)', 'বাজেট খাত (Category)', 'বরাদ্দকৃত টাকা (Allocated Amount BDT)', 'নোট (Notes)'];
        return $this->exportCsv($budgets, 'yearly_budget_' . str_replace('-', '_', $selectedYear), $headers);
    }

    public function exportBudgetsPdf(Request $request)
    {
        $this->checkPermission();

        $currentYear = date('Y') . '-' . (date('Y') + 1);
        $selectedYear = trim((string)$request->input('fiscal_year', $currentYear));

        $budgets = DB::table('yearly_budgets')->where('fiscal_year', $selectedYear)->orderBy('category', 'asc')->get()->map(fn($r) => (array)$r)->toArray();
        $reportTitle = 'Yearly Budget Allocation Report (' . $selectedYear . ')';
        $totalAmount = array_sum(array_column($budgets, 'allocated_amount'));

        extract(compact('budgets', 'reportTitle', 'totalAmount'));
        $viewFile = resource_path('views/portal/financials/print_report.php');
        if (file_exists($viewFile)) {
            ob_start();
            require $viewFile;
            return response(ob_get_clean());
        }
        abort(404);
    }

    private function exportCsv(array $data, string $filename, array $headers)
    {
        return response()->streamDownload(function () use ($data, $headers) {
            $out = fopen('php://output', 'w');
            fputs($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, $headers);
            foreach ($data as $row) {
                fputcsv($out, array_values($row));
            }
            fclose($out);
        }, "{$filename}.csv", [
            'Content-Type' => 'text/csv; charset=utf-8',
        ]);
    }
}
