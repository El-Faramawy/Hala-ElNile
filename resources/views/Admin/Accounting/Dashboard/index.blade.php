@extends('layouts.accounting.app')

@section('page_title')
    الرئيسية المالية
@endsection

@section('content')
<div class="row">
    <!-- Live Balances -->
    <div class="col-12 col-sm-6 col-xl-3 mb-3">
        <div class="card bg-primary img-card box-primary-shadow text-white h-100">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font font-weight-bold fs-20 fs-md-22">{{ number_format($stats['egp_total'], 2) }} ج.م</h2>
                        <p class="text-white mb-0 fs-13 font-weight-semibold">إجمالي السيولة (جنيه مصري)</p>
                    </div>
                    <div class="mr-auto">
                        <i class="fe fe-database text-white fs-26"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3 mb-3">
        <div class="card bg-success img-card box-success-shadow text-white h-100">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font font-weight-bold fs-20 fs-md-22">{{ number_format($stats['eur_total'], 2) }} €</h2>
                        <p class="text-white mb-0 fs-13 font-weight-semibold">رصيد خزينة اليورو</p>
                    </div>
                    <div class="mr-auto">
                        <i class="fe fe-dollar-sign text-white fs-26"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3 mb-3">
        <div class="card bg-danger img-card box-danger-shadow text-white h-100">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font font-weight-bold fs-20 fs-md-22">{{ number_format($stats['overdue_sum_eur'], 2) }} €</h2>
                        <p class="text-white mb-0 fs-13 font-weight-semibold">مستحقات متأخرة ({{ $stats['overdue_count'] }} دفعة)</p>
                    </div>
                    <div class="mr-auto">
                        <i class="fe fe-alert-triangle text-white fs-26"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3 mb-3">
        <div class="card bg-info img-card box-info-shadow text-white h-100">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font font-weight-bold fs-20 fs-md-22">{{ $stats['active_contracts_count'] }}</h2>
                        <p class="text-white mb-0 fs-13 font-weight-semibold">العقود النشطة المسجلة</p>
                    </div>
                    <div class="mr-auto">
                        <i class="fe fe-file-text text-white fs-26"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Bar -->
<div class="card mb-4">
    <div class="card-body p-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 10px;">
            <div class="d-flex align-items-center">
                <span class="badge badge-default p-2 mr-2"><i class="fe fe-zap text-warning fs-16"></i></span>
                <span class="font-weight-bold text-dark fs-14">إجراءات الوصول السريع:</span>
            </div>
            <div class="d-flex flex-wrap" style="gap: 8px;">
                <a href="{{ route('revenues.index') }}" class="btn btn-success btn-sm font-weight-bold">
                    <i class="fe fe-plus-circle ml-1"></i> قبض إيراد جديد
                </a>
                <a href="{{ route('expenses.index') }}" class="btn btn-danger btn-sm font-weight-bold">
                    <i class="fe fe-minus-circle ml-1"></i> صرف مصروف جديد
                </a>
                <a href="{{ route('conversions.index') }}" class="btn btn-primary btn-sm font-weight-bold">
                    <i class="fe fe-refresh-cw ml-1"></i> تحويل وتصريف عملة
                </a>
                <a href="{{ route('accounting.contracts.index') }}" class="btn btn-info btn-sm font-weight-bold">
                    <i class="fe fe-search ml-1"></i> بحث ومتابعة العقود
                </a>
                <a href="{{ route('accounting.overdue.index') }}" class="btn btn-outline-warning btn-sm font-weight-bold">
                    <i class="fe fe-bell ml-1"></i> الدفعات المتأخرة ({{ $stats['overdue_count'] }})
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Interactive Financial Summary Section -->
<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
        <h3 class="card-title font-weight-bold text-dark"><i class="fe fe-bar-chart-2 ml-1 text-primary"></i> الملخص المالي والتقارير الدورية</h3>
        <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
            <div class="btn-group btn-group-sm period-switcher" role="group">
                <button type="button" class="btn btn-outline-primary period-btn active" data-period="today">اليوم</button>
                <button type="button" class="btn btn-outline-primary period-btn" data-period="week">الأسبوع الحالي</button>
                <button type="button" class="btn btn-outline-primary period-btn" data-period="month">الشهر الحالي</button>
                <button type="button" class="btn btn-outline-primary period-btn" data-period="year">السنة الحالية</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- Filters Row -->
        <form id="summaryFilterForm" class="row align-items-end mb-4 p-3 bg-light rounded" style="border: 1px solid #e2e8f0;">
            <input type="hidden" name="period" id="selectedPeriod" value="today">

            <div class="col-md-3 col-sm-6 mb-2">
                <label class="form-label font-weight-semibold fs-12">من تاريخ:</label>
                <input type="date" name="date_from" id="dateFrom" class="form-control form-control-sm">
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <label class="form-label font-weight-semibold fs-12">إلى تاريخ:</label>
                <input type="date" name="date_to" id="dateTo" class="form-control form-control-sm">
            </div>
            <div class="col-md-4 col-sm-12 mb-2">
                <label class="form-label font-weight-semibold fs-12">آلية احتساب الأرباح:</label>
                <div class="d-flex align-items-center" style="gap: 15px;">
                    <label class="custom-control custom-radio mb-0">
                        <input type="radio" class="custom-control-input" name="calculation_mode" value="collected_actual" checked>
                        <span class="custom-control-label fs-12 font-weight-bold">المحصل الفعلي</span>
                    </label>
                    <label class="custom-control custom-radio mb-0">
                        <input type="radio" class="custom-control-input" name="calculation_mode" value="total_contract_value">
                        <span class="custom-control-label fs-12 font-weight-bold">قيمة العقود المسجلة</span>
                    </label>
                </div>
            </div>
            <div class="col-md-2 col-sm-12 mb-2 text-left">
                <button type="button" id="applyFilterBtn" class="btn btn-primary btn-sm w-100 font-weight-bold">
                    <i class="fe fe-filter ml-1"></i> تطبيق الفلتر
                </button>
            </div>
        </form>

        <!-- Live Calculated Output -->
        <div class="row text-center" id="summaryOutputCards">
            <div class="col-12 col-md-4 mb-3">
                <div class="p-4 border rounded bg-white box-shadow h-100">
                    <div class="text-muted small font-weight-bold mb-1">إجمالي الإيرادات في الفترة</div>
                    <h3 class="font-weight-bold text-success mb-1" id="sumRevenuesEgp">{{ number_format($summary['revenues_egp'], 2) }} ج.م</h3>
                    <div class="text-primary font-weight-bold" id="sumRevenuesEur">المحصل باليورو: {{ number_format($summary['revenues_eur'], 2) }} €</div>
                    <small class="text-muted" id="sumRevenuesEquiv">(ما يعادل إجمالي الإيرادات باليورو: {{ number_format($summary['revenues_eur_equivalent'], 2) }} €)</small>
                </div>
            </div>
            <div class="col-12 col-md-4 mb-3">
                <div class="p-4 border rounded bg-white box-shadow h-100">
                    <div class="text-muted small font-weight-bold mb-1">إجمالي المصروفات في الفترة</div>
                    <h3 class="font-weight-bold text-danger mb-1" id="sumExpensesEgp">{{ number_format($summary['expenses_egp'], 2) }} ج.م</h3>
                    <div class="text-danger font-weight-bold" id="sumExpensesEur">المصروف باليورو: {{ number_format($summary['expenses_eur'], 2) }} €</div>
                    <small class="text-muted">شامل المصاريف الإدارية وفروق التحويل</small>
                </div>
            </div>
            <div class="col-12 col-md-4 mb-3">
                <div class="p-4 border rounded bg-white box-shadow h-100">
                    <div class="text-muted small font-weight-bold mb-1">صافي الأرباح في الفترة</div>
                    <h3 class="font-weight-bold text-dark mb-1" id="sumNetProfitEgp">{{ number_format($summary['net_profit_egp'], 2) }} ج.م</h3>
                    <div class="font-weight-bold text-success fs-16" id="sumNetProfitEur">صافي اليورو: {{ number_format($summary['net_profit_eur'], 2) }} €</div>
                    <small class="text-muted d-block" id="sumNetProfitEquiv">(إجمالي المعادل باليورو: {{ number_format($summary['net_profit_eur_equivalent'], 2) }} €)</small>
                    <small class="text-muted">الإيرادات المحسوبة مطروحاً منها المصروفات</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Treasuries Breakdown -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold text-dark"><i class="fe fe-database ml-1 text-info"></i> أرصدة الخزائن وحسابات النقدية اللحظية</h3>
        <a href="{{ route('treasuries.index') }}" class="btn btn-sm btn-outline-primary">إدارة الخزائن</a>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($stats['treasuries'] as $treasury)
            <div class="col-12 col-sm-6 col-lg-4 mb-3">
                <div class="p-3 border rounded d-flex align-items-center justify-content-between bg-white h-100">
                    <div>
                        <h5 class="mb-1 font-weight-bold text-dark">{{ $treasury->name }}</h5>
                        <span class="badge {{ $treasury->currency === 'EUR' ? 'badge-primary' : 'badge-info' }} px-2 py-1">
                            {{ $treasury->currency === 'EUR' ? '€ يورو' : 'ج.م جنيه مصري' }}
                        </span>
                    </div>
                    <div class="text-left">
                        <span class="fs-18 font-weight-bold {{ (float)$treasury->balance >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format((float) $treasury->balance, 2) }}
                        </span>
                        <small class="d-block text-muted">{{ $treasury->currency === 'EUR' ? 'EUR' : 'EGP' }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('admin_js')
<script>
    $(document).ready(function() {
        $('.period-btn').on('click', function() {
            $('.period-btn').removeClass('active btn-primary').addClass('btn-outline-primary');
            $(this).addClass('active btn-primary').removeClass('btn-outline-primary');
            $('#selectedPeriod').val($(this).data('period'));
            $('#dateFrom').val('');
            $('#dateTo').val('');
            fetchSummary();
        });

        $('#applyFilterBtn').on('click', function() {
            if ($('#dateFrom').val() || $('#dateTo').val()) {
                $('.period-btn').removeClass('active btn-primary').addClass('btn-outline-primary');
                $('#selectedPeriod').val('custom');
            }
            fetchSummary();
        });

        $('input[name="calculation_mode"]').on('change', function() {
            fetchSummary();
        });

        function fetchSummary() {
            var formData = $('#summaryFilterForm').serialize();
            $.ajax({
                url: "{{ route('accounting.summary') }}",
                type: 'GET',
                data: formData,
                beforeSend: function() {
                    $('#summaryOutputCards').css('opacity', '0.5');
                },
                success: function(res) {
                    $('#summaryOutputCards').css('opacity', '1');
                    $('#sumRevenuesEgp').text(Number(res.revenues_egp).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' ج.م');
                    $('#sumRevenuesEur').text('المحصل باليورو: ' + Number(res.revenues_eur).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' €');
                    $('#sumRevenuesEquiv').text('(ما يعادل إجمالي الإيرادات باليورو: ' + Number(res.revenues_eur_equivalent).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' €)');
                    
                    $('#sumExpensesEgp').text(Number(res.expenses_egp).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' ج.م');
                    $('#sumExpensesEur').text('المصروف باليورو: ' + Number(res.expenses_eur).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' €');

                    $('#sumNetProfitEgp').text(Number(res.net_profit_egp).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' ج.م');
                    $('#sumNetProfitEur').text('صافي اليورو: ' + Number(res.net_profit_eur).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' €');
                    $('#sumNetProfitEquiv').text('(إجمالي المعادل باليورو: ' + Number(res.net_profit_eur_equivalent).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' €)');
                },
                error: function() {
                    $('#summaryOutputCards').css('opacity', '1');
                }
            });
        }
    });
</script>
@endpush
