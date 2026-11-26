@extends('layouts.accounting.app')

@section('page_title')
    كشف الحساب المالي للمسافر: {{ $contractTracker->applicant_name }}
@endsection

@push('admin_css')
<style>
    @media print {
        .no-print, .nav-tabs, .btn, .modal, .app-sidebar, .app-header, .page-header {
            display: none !important;
        }
        .tab-content > .tab-pane {
            display: none !important;
        }
        .tab-content > .tab-pane.active {
            display: block !important;
            opacity: 1 !important;
        }
        .app-content {
            margin: 0 !important;
            padding: 0 !important;
        }
    }
</style>
@endpush

@section('content')
@php
    $totalPriceEur = (float) $contractTracker->total_price_eur;
    $collectedEur = (float) $contractTracker->revenues->sum('eur_equivalent');
    $remainingEur = max(0.0, $totalPriceEur - $collectedEur);
    $percent = $totalPriceEur > 0 ? min(100, (int) round(($collectedEur / $totalPriceEur) * 100)) : 0;

    $cityName = $contractTracker->city_name ?? 'إيطاليا';
    $jobTitle = $contractTracker->job_title ?? '';
    $jobDisplay = ($cityName !== '' && str_contains($jobTitle, $cityName))
        ? $jobTitle
        : trim($jobTitle.' - '.$cityName, ' -');

    $hasSupplierAllocation = $contractTracker->supplier_id !== null || (float) ($contractTracker->supplier_cost_eur ?? 0) > 0;
    $supplierCostEur = (float) ($contractTracker->supplier_cost_eur ?? 0);
    $supplierPaidEur = $contractTracker->supplier_id
        ? (float) $contractTracker->expenses
            ->where('supplier_id', $contractTracker->supplier_id)
            ->where('currency', 'EUR')
            ->sum('amount')
        : 0.0;
    $supplierRemainingEur = max(0.0, $supplierCostEur - $supplierPaidEur);
    $supplierPercent = $supplierCostEur > 0 ? min(100, (int) round(($supplierPaidEur / $supplierCostEur) * 100)) : 0;
@endphp

<div class="row">
    <div class="col-12 mb-3 d-flex flex-wrap justify-content-between align-items-center no-print" style="gap: 10px;">
        <a href="{{ route('accounting.contracts.index') }}" class="btn btn-outline-primary btn-sm font-weight-bold">
            <i class="fe fe-arrow-right ml-1"></i> العودة لقائمة العقود
        </a>
        <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
            <button type="button" class="btn btn-outline-warning btn-sm font-weight-bold" data-toggle="modal" data-target="#editLedgerPriceModal">
                <i class="fa fa-edit ml-1"></i> تعديل سعر العقد والمورد
            </button>
            <button onclick="window.print()" class="btn btn-dark btn-sm font-weight-bold">
                <i class="fe fe-printer ml-1"></i> طباعة الكشف الحالي
            </button>
        </div>
    </div>

    <!-- Main 2 Ledger Tabs: Company View vs Applicant View -->
    <div class="col-12">
        <ul class="nav nav-tabs mb-3 bg-white p-2 rounded shadow-sm border no-print" id="mainLedgerTabs" role="tablist" style="gap: 8px;">
            <li class="nav-item">
                <a class="nav-link active font-weight-bold" id="company-ledger-tab" data-toggle="tab" href="#companyLedgerPane" role="tab">
                    <i class="fe fe-briefcase ml-1 text-primary"></i> كشف حساب الشركة (داخلي — المورد والمصروفات والمجدولة)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" id="applicant-ledger-tab" data-toggle="tab" href="#applicantLedgerPane" role="tab">
                    <i class="fe fe-user ml-1 text-success"></i> كشف حساب المتقدم / العميل (للطباعة)
                </a>
            </li>
        </ul>

        <div class="tab-content" id="mainLedgerTabsContent">
            <!-- =========================================================
                 TAB 1: COMPANY INTERNAL LEDGER
                 ========================================================= -->
            <div class="tab-pane fade show active" id="companyLedgerPane" role="tabpanel">
                <!-- Contract Financial Summary Card -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h3 class="card-title font-weight-bold text-white mb-0">
                            <i class="fe fe-user ml-1"></i> كشف حساب الشركة — {{ $contractTracker->applicant_name }} (كود: {{ $contractTracker->contract_code }})
                        </h3>
                        <span class="badge badge-light text-primary font-weight-bold px-3 py-1 fs-13">
                            {{ $jobDisplay }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-12 col-sm-6 col-md-3 mb-3">
                                <div class="p-3 bg-light rounded border h-100">
                                    <small class="text-muted font-weight-bold d-block">قيمة العقد المتفق عليها:</small>
                                    <h3 class="font-weight-bold text-dark mb-1 mt-1">{{ number_format($totalPriceEur, 2) }} €</h3>
                                    <button type="button" class="btn btn-xs btn-outline-warning font-weight-bold mt-1 no-print" data-toggle="modal" data-target="#editLedgerPriceModal">
                                        <i class="fa fa-edit ml-1"></i> تعديل السعر
                                    </button>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3 mb-3">
                                <div class="p-3 bg-light rounded border h-100">
                                    <small class="text-muted font-weight-bold d-block">إجمالي المسدد من العميل:</small>
                                    <h3 class="font-weight-bold text-success mb-0 mt-1">{{ number_format($collectedEur, 2) }} €</h3>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3 mb-3">
                                <div class="p-3 bg-light rounded border h-100">
                                    <small class="text-muted font-weight-bold d-block">المتبقي على العميل:</small>
                                    <h3 class="font-weight-bold text-danger mb-0 mt-1">{{ number_format($remainingEur, 2) }} €</h3>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3 mb-3">
                                <div class="p-3 bg-light rounded border h-100">
                                    <small class="text-muted font-weight-bold d-block">نسبة السداد:</small>
                                    <h3 class="font-weight-bold text-primary mb-0 mt-1">{{ $percent }}%</h3>
                                </div>
                            </div>
                        </div>

                        <div class="progress mt-2" style="height: 12px; border-radius: 6px;">
                            <div class="progress-bar {{ $percent >= 100 ? 'bg-success' : ($percent >= 50 ? 'bg-info' : 'bg-warning') }}" role="progressbar" style="width: {{ $percent }}%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Supplier Allocation Card (Hidden from print unless user checks the box) -->
                @if($hasSupplierAllocation)
                    <div class="card border-warning mb-4 d-print-none" id="supplierAllocationCard">
                        <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center" style="gap: 10px;">
                            <div class="d-flex align-items-center" style="gap: 8px;">
                                <i class="fa fa-truck text-warning fs-18"></i>
                                <h5 class="mb-0 font-weight-bold text-dark">
                                    مستحقات المورد: <span class="text-primary">{{ $contractTracker->supplier?->name ?? 'مورد غير محدد' }}</span>
                                </h5>
                            </div>
                            <label class="custom-control custom-checkbox mb-0 no-print" style="cursor: pointer;">
                                <input type="checkbox" class="custom-control-input" id="toggleSupplierPrint">
                                <span class="custom-control-label font-weight-bold text-dark fs-13">إظهار مستحقات المورد عند الطباعة</span>
                            </label>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-12 col-md-4 mb-2">
                                    <div class="p-3 bg-light rounded border">
                                        <small class="text-muted font-weight-bold d-block">نصيب / تكلفة المورد المتفق عليها:</small>
                                        <h4 class="font-weight-bold text-dark mb-0 mt-1">{{ number_format($supplierCostEur, 2) }} €</h4>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 mb-2">
                                    <div class="p-3 bg-light rounded border">
                                        <small class="text-muted font-weight-bold d-block">المدفوع للمورد حتى الآن:</small>
                                        <h4 class="font-weight-bold text-success mb-0 mt-1">{{ number_format($supplierPaidEur, 2) }} €</h4>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 mb-2">
                                    <div class="p-3 bg-light rounded border">
                                        <small class="text-muted font-weight-bold d-block">المتبقي للمورد:</small>
                                        <h4 class="font-weight-bold text-danger mb-0 mt-1">{{ number_format($supplierRemainingEur, 2) }} €</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 10px; border-radius: 5px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $supplierPercent }}%;"></div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Section 1: Scheduled Installments -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title font-weight-bold mb-0">
                            <i class="fe fe-calendar ml-1 text-primary"></i> الدفعات المجدولة ({{ $contractTracker->installments->count() }})
                        </h4>
                        <button type="button" class="btn btn-primary btn-sm font-weight-bold no-print" data-toggle="modal" data-target="#addInstallmentModal">
                            <i class="fe fe-plus ml-1"></i> جدولة دفعة جديدة
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>عنوان الدفعة</th>
                                        <th>القيمة باليورو (€)</th>
                                        <th>تاريخ الاستحقاق</th>
                                        <th>تاريخ السداد الفعلي</th>
                                        <th>الحالة</th>
                                        <th>ملاحظات</th>
                                        <th class="no-print">تحكم</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contractTracker->installments as $installment)
                                    <tr>
                                        <td class="font-weight-bold text-dark">{{ $installment->title }}</td>
                                        <td class="font-weight-bold text-primary fs-14">{{ number_format((float)$installment->amount_eur, 2) }} €</td>
                                        <td>{{ $installment->due_date?->format('Y-m-d') }}</td>
                                        <td>{{ $installment->paid_at?->format('Y-m-d') ?? '—' }}</td>
                                        <td>
                                            @if($installment->status === 'paid')
                                                <span class="badge badge-success px-2 py-1"><i class="fa fa-check mr-1"></i> تم السداد</span>
                                            @elseif(\Carbon\Carbon::parse($installment->due_date)->isPast())
                                                <span class="badge badge-danger px-2 py-1"><i class="fa fa-clock-o mr-1"></i> متأخر</span>
                                            @else
                                                <span class="badge badge-warning px-2 py-1"><i class="fa fa-hourglass-half mr-1"></i> ساري</span>
                                            @endif
                                        </td>
                                        <td>{{ $installment->notes ?? '—' }}</td>
                                        <td class="no-print">
                                            @if($installment->status !== 'paid')
                                                <button type="button" class="btn btn-sm btn-success mark-paid-btn" data-id="{{ $installment->id }}" title="تأكيد السداد">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-danger delete-installment-btn" data-id="{{ $installment->id }}" title="حذف">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted p-4">لا توجد دفعات مجدولة حتى الآن لهذا العقد.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Direct Expenses on Contract -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title font-weight-bold mb-0">
                            <i class="fe fe-upload ml-1 text-danger"></i> المصاريف المباشرة على العقد ({{ $contractTracker->expenses->count() }})
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th># السند</th>
                                        <th>التاريخ</th>
                                        <th>بند المصروف والمستفيد</th>
                                        <th>المبلغ المنصرف</th>
                                        <th>الخزينة المنصرف منها</th>
                                        <th>التفاصيل والملاحظات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contractTracker->expenses as $expense)
                                    <tr>
                                        <td><span class="badge badge-default">#{{ $expense->id }}</span></td>
                                        <td>{{ $expense->transaction_date?->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="font-weight-bold text-dark">
                                                @if($expense->expenseType?->expenseCategory)
                                                    <span class="text-primary small">{{ $expense->expenseType->expenseCategory->name }} — </span>
                                                @endif
                                                {{ $expense->expenseType?->name ?? 'مصروف عام' }}
                                            </div>
                                            @if($expense->supplier)
                                                <div class="mt-1">
                                                    <span class="badge badge-secondary px-2 py-1" style="font-size: 11px;">
                                                        <i class="fa fa-truck ml-1"></i> {{ $expense->supplier->name }}
                                                    </span>
                                                </div>
                                            @elseif($expense->admin)
                                                <div class="mt-1">
                                                    <span class="badge badge-info px-2 py-1" style="font-size: 11px;">
                                                        <i class="fa fa-user ml-1"></i> {{ $expense->admin->name }}
                                                    </span>
                                                </div>
                                            @elseif($expense->other_details)
                                                <small class="text-muted d-block mt-1">{{ $expense->other_details }}</small>
                                            @endif
                                        </td>
                                        <td class="font-weight-bold text-danger">{{ number_format((float)$expense->amount, 2) }} {{ $expense->currency === 'EUR' ? '€' : 'ج.م' }}</td>
                                        <td><span class="badge badge-info">{{ $expense->treasury?->name }}</span></td>
                                        <td>{{ $expense->other_details ?? ($expense->notes ?? '—') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted p-4">لا توجد مصاريف مباشرة مسجلة على هذا العقد.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Collected Revenues -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title font-weight-bold mb-0">
                            <i class="fe fe-download ml-1 text-success"></i> سندات القبض المحصلة ({{ $contractTracker->revenues->count() }})
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th># السند</th>
                                        <th>التاريخ</th>
                                        <th>المبلغ المسدد</th>
                                        <th>ما يعادله باليورو (€)</th>
                                        <th>الخزينة المودع بها</th>
                                        <th>البيان والملاحظات</th>
                                        <th class="no-print">إجراء</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contractTracker->revenues as $revenue)
                                    <tr>
                                        <td><span class="badge badge-default">#{{ $revenue->id }}</span></td>
                                        <td>{{ $revenue->transaction_date?->format('Y-m-d') }}</td>
                                        <td class="font-weight-bold {{ $revenue->received_currency === 'EUR' ? 'text-primary' : 'text-success' }}">
                                            {{ number_format((float)$revenue->amount, 2) }} {{ $revenue->received_currency === 'EUR' ? '€' : 'ج.م' }}
                                        </td>
                                        <td class="font-weight-bold text-dark">{{ number_format((float)$revenue->eur_equivalent, 2) }} €</td>
                                        <td><span class="badge badge-info">{{ $revenue->treasury?->name }}</span></td>
                                        <td>{{ $revenue->notes ?? '—' }}</td>
                                        <td class="no-print">
                                            <a href="{{ route('revenues.receipt', $revenue->id) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                <i class="fa fa-print mr-1"></i> سند القبض
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted p-4">لم يتم تسجيل أي سندات قبض لهذا العقد حتى الآن.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- =========================================================
                 TAB 2: APPLICANT / CLIENT STATEMENT (CLEAN FOR PRINT)
                 ========================================================= -->
            <div class="tab-pane fade" id="applicantLedgerPane" role="tabpanel">
                <div class="card mb-4">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h3 class="card-title font-weight-bold text-white mb-0">
                            <i class="fe fe-user ml-1"></i> كشف حساب المسافر: {{ $contractTracker->applicant_name }} (كود: {{ $contractTracker->contract_code }})
                        </h3>
                        <span class="badge badge-light text-success font-weight-bold px-3 py-1 fs-13">
                            {{ $jobDisplay }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-12 col-sm-6 col-md-3 mb-3">
                                <div class="p-3 bg-light rounded border h-100">
                                    <small class="text-muted font-weight-bold d-block">قيمة العقد المتفق عليها:</small>
                                    <h3 class="font-weight-bold text-dark mb-0 mt-1">{{ number_format($totalPriceEur, 2) }} €</h3>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3 mb-3">
                                <div class="p-3 bg-light rounded border h-100">
                                    <small class="text-muted font-weight-bold d-block">إجمالي المسدد (ما يعادله باليورو):</small>
                                    <h3 class="font-weight-bold text-success mb-0 mt-1">{{ number_format($collectedEur, 2) }} €</h3>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3 mb-3">
                                <div class="p-3 bg-light rounded border h-100">
                                    <small class="text-muted font-weight-bold d-block">المتبقي المطلوب تحصيله:</small>
                                    <h3 class="font-weight-bold text-danger mb-0 mt-1">{{ number_format($remainingEur, 2) }} €</h3>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3 mb-3">
                                <div class="p-3 bg-light rounded border h-100">
                                    <small class="text-muted font-weight-bold d-block">نسبة السداد:</small>
                                    <h3 class="font-weight-bold text-primary mb-0 mt-1">{{ $percent }}%</h3>
                                </div>
                            </div>
                        </div>

                        <div class="progress mt-2" style="height: 12px; border-radius: 6px;">
                            <div class="progress-bar {{ $percent >= 100 ? 'bg-success' : ($percent >= 50 ? 'bg-info' : 'bg-warning') }}" role="progressbar" style="width: {{ $percent }}%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Applicant Revenues -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title font-weight-bold mb-0">
                            <i class="fe fe-check-circle ml-1 text-success"></i> سجل الدفعات المسددة من العميل ({{ $contractTracker->revenues->count() }})
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th># السند</th>
                                        <th>تاريخ السداد</th>
                                        <th>المبلغ المدفوع</th>
                                        <th>ما يعادله باليورو (€)</th>
                                        <th>البيان والملاحظات</th>
                                        <th class="no-print">سند القبض</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contractTracker->revenues as $revenue)
                                    <tr>
                                        <td><span class="badge badge-default">#{{ $revenue->id }}</span></td>
                                        <td>{{ $revenue->transaction_date?->format('Y-m-d') }}</td>
                                        <td class="font-weight-bold {{ $revenue->received_currency === 'EUR' ? 'text-primary' : 'text-success' }}">
                                            {{ number_format((float)$revenue->amount, 2) }} {{ $revenue->received_currency === 'EUR' ? '€' : 'ج.م' }}
                                        </td>
                                        <td class="font-weight-bold text-dark">{{ number_format((float)$revenue->eur_equivalent, 2) }} €</td>
                                        <td>{{ $revenue->notes ?? '—' }}</td>
                                        <td class="no-print">
                                            <a href="{{ route('revenues.receipt', $revenue->id) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                <i class="fa fa-print mr-1"></i> طباعة
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted p-4">لم يتم تسجيل أي دفعات مسددة حتى الآن.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Applicant Scheduled Installments -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title font-weight-bold mb-0">
                            <i class="fe fe-calendar ml-1 text-primary"></i> جدول الدفعات المستحقة ({{ $contractTracker->installments->count() }})
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>الدفعة</th>
                                        <th>القيمة باليورو (€)</th>
                                        <th>تاريخ الاستحقاق</th>
                                        <th>الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contractTracker->installments as $installment)
                                    <tr>
                                        <td class="font-weight-bold">{{ $installment->title }}</td>
                                        <td class="font-weight-bold text-primary">{{ number_format((float)$installment->amount_eur, 2) }} €</td>
                                        <td>{{ $installment->due_date?->format('Y-m-d') }}</td>
                                        <td>
                                            @if($installment->status === 'paid')
                                                <span class="badge badge-success px-2 py-1">تم السداد</span>
                                            @elseif(\Carbon\Carbon::parse($installment->due_date)->isPast())
                                                <span class="badge badge-danger px-2 py-1">متأخر</span>
                                            @else
                                                <span class="badge badge-warning px-2 py-1">مستحق لاحقاً</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted p-4">لا توجد دفعات مجدولة.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Installment Modal -->
<div class="modal fade" id="addInstallmentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold">جدولة دفعة مالية جديدة</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="addInstallmentForm">
                @csrf
                <input type="hidden" name="contract_tracker_id" value="{{ $contractTracker->id }}">
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">عنوان الدفعة <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required placeholder="مثال: دفعة استلام النولا أوستا / دفعة الفيزا">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">المبلغ المطلوب باليورو (€) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0.01" name="amount_eur" class="form-control font-weight-bold text-primary" required placeholder="500.00">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">تاريخ الاستحقاق <span class="text-danger">*</span></label>
                        <input type="date" name="due_date" class="form-control" required>
                    </div>

                    <div class="form-group mb-0">
                        <label class="form-label font-weight-bold">ملاحظات</label>
                        <input type="text" name="notes" class="form-control" placeholder="اختياري...">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary font-weight-bold" id="saveInstallmentBtn">حفظ الجدولة</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Mark Paid Modal -->
<div class="modal fade" id="markPaidModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-weight-bold">تأكيد استلام الدفعة المجدولة</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="markPaidForm">
                @csrf
                <input type="hidden" id="paidInstallmentId">
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">الخزينة المستلمة <span class="text-danger">*</span></label>
                        <select name="treasury_id" class="form-control" required>
                            @foreach($treasuries as $treasury)
                                <option value="{{ $treasury->id }}">{{ $treasury->name }} ({{ $treasury->currency }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">تاريخ السداد الفعلي <span class="text-danger">*</span></label>
                        <input type="date" name="paid_at" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success font-weight-bold" id="confirmPaidBtn">تأكيد السداد وإيداع في الخزينة</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Contract Price & Supplier Modal -->
<div class="modal fade" id="editLedgerPriceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold">تعديل قيمة العقد والمورد (€)</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="editLedgerPriceForm">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">إجمالي قيمة العقد باليورو (€) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" step="0.01" min="0" name="total_price_eur" class="form-control font-weight-bold fs-16 text-primary" value="{{ $totalPriceEur }}" required>
                            <div class="input-group-append">
                                <span class="input-group-text font-weight-bold">€</span>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">المورد المرتبط بالعقد <small class="text-muted">(اختياري - مثال: د/ فايز)</small></label>
                        <select name="supplier_id" class="form-control">
                            <option value="">-- بدون تحديد مورد --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ (int) $contractTracker->supplier_id === (int) $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-0">
                        <label class="form-label font-weight-bold">تكلفة / مستحقات المورد من العقد (€) <small class="text-muted">(اختياري)</small></label>
                        <div class="input-group">
                            <input type="number" step="0.01" min="0" name="supplier_cost_eur" class="form-control font-weight-bold text-danger" value="{{ $supplierCostEur > 0 ? $supplierCostEur : '' }}" placeholder="0.00">
                            <div class="input-group-append">
                                <span class="input-group-text font-weight-bold">€</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary font-weight-bold" id="saveLedgerPriceBtn">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('admin_js')
<script>
    $('#toggleSupplierPrint').on('change', function() {
        if ($(this).is(':checked')) {
            $('#supplierAllocationCard').removeClass('d-print-none');
        } else {
            $('#supplierAllocationCard').addClass('d-print-none');
        }
    });

    $('#addInstallmentForm').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#saveInstallmentBtn');
        $btn.prop('disabled', true);

        $.ajax({
            url: "{{ route('accounting.installments.store') }}",
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                $('#addInstallmentModal').modal('hide');
                Swal.fire({ icon: 'success', title: 'تم بنجاح', text: res.message }).then(() => {
                    location.reload();
                });
            },
            error: function() {
                $btn.prop('disabled', false);
                Swal.fire({ icon: 'error', title: 'خطأ', text: 'تأكد من البيانات المدخلة.' });
            }
        });
    });

    $(document).on('click', '.mark-paid-btn', function() {
        $('#paidInstallmentId').val($(this).data('id'));
        $('#markPaidModal').modal('show');
    });

    $('#markPaidForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#paidInstallmentId').val();
        var $btn = $('#confirmPaidBtn');
        $btn.prop('disabled', true);

        $.ajax({
            url: "{{ url('accounting/overdue') }}/" + id + "/mark-paid",
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                $('#markPaidModal').modal('hide');
                Swal.fire({ icon: 'success', title: 'تم السداد', text: res.message }).then(() => {
                    location.reload();
                });
            },
            error: function() {
                $btn.prop('disabled', false);
                Swal.fire({ icon: 'error', title: 'خطأ', text: 'حدث خطأ أثناء تأكيد السداد.' });
            }
        });
    });

    $(document).on('click', '.delete-installment-btn', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'حذف الدفعة المجدولة؟',
            text: 'لن تتمكن من استرجاعها بعد الحذف.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'نعم، حذف',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('accounting/contracts/installments') }}/" + id,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function() {
                        location.reload();
                    }
                });
            }
        });
    });

    $('#editLedgerPriceForm').off('submit').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#saveLedgerPriceBtn');
        $btn.prop('disabled', true);

        $.ajax({
            url: "{{ route('accounting.contracts.updatePrice', $contractTracker->id) }}",
            type: 'POST',
            headers: { 'Accept': 'application/json' },
            data: $(this).serialize(),
            success: function(res) {
                $('#editLedgerPriceModal').modal('hide');
                Swal.fire({ icon: 'success', title: 'تم التحديث', text: res.message }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                $btn.prop('disabled', false);
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'حدث خطأ أثناء التحديث.';
                Swal.fire({ icon: 'error', title: 'خطأ', text: msg });
            }
        });
    });
</script>
@endpush
