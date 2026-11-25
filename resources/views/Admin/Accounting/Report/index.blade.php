@extends('layouts.accounting.app')

@section('page_title')
    التقارير المالية والأرباح والخسائر
@endsection

@section('content')
<div class="row">
    <!-- Filter Card (Responsive & Collapsible) -->
    <div class="col-12 mb-3">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; background: #ffffff;">
            <div class="card-header bg-white py-2 px-4 d-flex justify-content-between align-items-center" style="border-radius: 12px; cursor: pointer;" data-toggle="collapse" data-target="#reportFilterCollapse" aria-expanded="true" aria-controls="reportFilterCollapse">
                <div class="d-flex align-items-center" style="gap: 8px;">
                    <span class="badge badge-light border text-primary p-2" style="border-radius: 8px;">
                        <i class="fe fe-sliders fs-14"></i>
                    </span>
                    <span class="font-weight-bold text-dark fs-14">تصفية وفلترة التقارير المالية</span>
                    <span class="badge badge-primary px-2 py-1 fs-11">
                        {{ $summary['start_date'] ? ($summary['start_date'].' → '.($summary['end_date'] ?? 'الآن')) : 'كل الفترات' }}
                    </span>
                </div>
                <button class="btn btn-sm btn-outline-primary font-weight-bold" type="button">
                    <i class="fe fe-filter ml-1"></i> خيارات الفلترة <i class="fa fa-chevron-down mr-1"></i>
                </button>
            </div>

            <div class="card-body p-3 p-md-4 collapse show border-top" id="reportFilterCollapse">
                <form method="GET" action="{{ route('accounting.reports.index') }}" id="reportFilterForm">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-3">
                            <label class="form-label font-weight-bold fs-12 text-muted mb-1">
                                <i class="fe fe-clock text-primary ml-1"></i> الفترة الزمنية السريعة:
                            </label>
                            <select name="period" class="form-control form-control-solid" id="reportPeriodSelect">
                                <option value="today" {{ request('period') === 'today' ? 'selected' : '' }}>اليوم</option>
                                <option value="week" {{ request('period') === 'week' ? 'selected' : '' }}>الأسبوع الحالي</option>
                                <option value="month" {{ request('period', 'month') === 'month' ? 'selected' : '' }}>الشهر الحالي</option>
                                <option value="year" {{ request('period') === 'year' ? 'selected' : '' }}>السنة الحالية</option>
                                <option value="all" {{ request('period') === 'all' ? 'selected' : '' }}>كل الفترات (منذ البداية)</option>
                                <option value="custom" {{ request('period') === 'custom' ? 'selected' : '' }}>فترة مخصصة (من - إلى)</option>
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-3">
                            <label class="form-label font-weight-bold fs-12 text-muted mb-1">
                                <i class="fe fe-calendar text-primary ml-1"></i> من تاريخ:
                            </label>
                            <input type="date" name="date_from" id="reportDateFrom" value="{{ request('period') === 'custom' ? request('date_from') : $summary['start_date'] }}" class="form-control form-control-solid">
                        </div>

                        <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-3">
                            <label class="form-label font-weight-bold fs-12 text-muted mb-1">
                                <i class="fe fe-calendar text-primary ml-1"></i> إلى تاريخ:
                            </label>
                            <input type="date" name="date_to" id="reportDateTo" value="{{ request('period') === 'custom' ? request('date_to') : $summary['end_date'] }}" class="form-control form-control-solid">
                        </div>

                        <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-3">
                            <label class="form-label font-weight-bold fs-12 text-muted mb-1">
                                <i class="fe fe-bar-chart-2 text-success ml-1"></i> آلية احتساب الأرباح:
                            </label>
                            <select name="calculation_mode" id="reportCalculationMode" class="form-control form-control-solid">
                                <option value="collected_actual" {{ request('calculation_mode', 'collected_actual') === 'collected_actual' ? 'selected' : '' }}>
                                    المحصل الفعلي في الخزائن
                                </option>
                                <option value="total_contract_value" {{ request('calculation_mode') === 'total_contract_value' ? 'selected' : '' }}>
                                    إجمالي قيمة العقود المسجلة
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="row pt-2 border-top">
                        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 mr-auto">
                            <div class="d-flex flex-wrap" style="gap: 8px;">
                                <button type="submit" class="btn btn-primary font-weight-bold flex-fill py-2">
                                    <i class="fe fe-search ml-1"></i> عرض التقرير
                                </button>
                                <a href="{{ route('accounting.reports.index') }}" class="btn btn-outline-secondary font-weight-bold py-2 px-3" title="إعادة ضبط">
                                    <i class="fe fe-rotate-ccw ml-1"></i> إعادة ضبط
                                </a>
                                <button type="button" onclick="window.print()" class="btn btn-outline-dark font-weight-bold py-2 px-3" title="طباعة">
                                    <i class="fe fe-printer ml-1"></i> طباعة
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- P&L Statement Overview -->
    <div class="col-12 mb-4">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold text-white mb-0">
                    <i class="fe fe-pie-chart ml-1"></i> قائمة الأرباح والخسائر التقديرية
                </h3>
                <span class="badge badge-light text-primary font-weight-bold px-3 py-1 fs-12">
                    الفترة: {{ $summary['start_date'] ?? 'البداية' }} إلى {{ $summary['end_date'] ?? 'الآن' }}
                </span>
            </div>
            <div class="card-body">
                <div class="row text-center mb-3">
                    <div class="col-12 col-md-4 mb-3">
                        <div class="p-3 bg-light rounded border h-100">
                            <small class="text-muted font-weight-bold d-block">إجمالي الإيرادات:</small>
                            <h4 class="font-weight-bold text-success mb-1">{{ number_format($summary['revenues_egp'], 2) }} ج.م</h4>
                            <span class="text-primary font-weight-bold d-block">المحصل باليورو: {{ number_format($summary['revenues_eur'], 2) }} €</span>
                            <small class="text-muted">(إجمالي المعادل باليورو: {{ number_format($summary['revenues_eur_equivalent'], 2) }} €)</small>
                        </div>
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <div class="p-3 bg-light rounded border h-100">
                            <small class="text-muted font-weight-bold d-block">إجمالي المصروفات:</small>
                            <h4 class="font-weight-bold text-danger mb-1">{{ number_format($summary['expenses_egp'], 2) }} ج.م</h4>
                            <span class="text-danger font-weight-bold">المصروف باليورو: {{ number_format($summary['expenses_eur'], 2) }} €</span>
                        </div>
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <div class="p-3 bg-light rounded border h-100">
                            <small class="text-muted font-weight-bold d-block">صافي الأرباح المحققة:</small>
                            <h4 class="font-weight-bold text-dark mb-1">{{ number_format($summary['net_profit_egp'], 2) }} ج.م</h4>
                            <span class="text-success font-weight-bold fs-16 d-block">صافي اليورو: {{ number_format($summary['net_profit_eur'], 2) }} €</span>
                            <small class="text-muted d-block mt-1">(إجمالي المعادل باليورو: {{ number_format($summary['net_profit_eur_equivalent'], 2) }} €)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Per-Contract Profitability Table -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">تقرير ربحية كل عقد على حدة (Profit per Contract)</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>كود العقد</th>
                                <th>اسم المسافر</th>
                                <th>الوظيفة والمدينة</th>
                                <th>قيمة العقد (€)</th>
                                <th>المحصل من العميل (€)</th>
                                <th>المصروفات المباشرة (€)</th>
                                <th>صافي ربح العقد (€)</th>
                                <th>نسبة التحصيل</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contracts as $contract)
                            @php
                                $totalEur = (float) $contract->total_price_eur;
                                $collectedEur = (float) $contract->revenues->sum('eur_equivalent');
                                $expensesEur = (float) $contract->expenses->where('currency', 'EUR')->sum('amount');
                                $profitEur = $collectedEur - $expensesEur;
                                $pct = $totalEur > 0 ? min(100, (int) round(($collectedEur / $totalEur) * 100)) : 0;
                            @endphp
                            <tr>
                                @php
                                    $cityName = $contract->city_name ?? 'إيطاليا';
                                    $jobTitle = $contract->job_title ?? '';
                                    $jobDisplay = ($cityName !== '' && str_contains($jobTitle, $cityName))
                                        ? $jobTitle
                                        : trim($jobTitle.' - '.$cityName, ' -');
                                @endphp
                                <td><span class="badge badge-primary">{{ $contract->contract_code }}</span></td>
                                <td class="font-weight-bold text-dark">{{ $contract->applicant_name }}</td>
                                <td>{{ $jobDisplay }}</td>
                                <td class="font-weight-bold">{{ number_format($totalEur, 2) }} €</td>
                                <td class="font-weight-bold text-success">{{ number_format($collectedEur, 2) }} €</td>
                                <td class="font-weight-bold text-danger">{{ number_format($expensesEur, 2) }} €</td>
                                <td class="font-weight-bold {{ $profitEur >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($profitEur, 2) }} €
                                </td>
                                <td>
                                    <div class="d-flex align-items-center" style="gap: 5px;">
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            <div class="progress-bar {{ $pct >= 100 ? 'bg-success' : ($pct >= 50 ? 'bg-info' : 'bg-warning') }}" style="width: {{ $pct }}%;"></div>
                                        </div>
                                        <span class="small font-weight-bold">{{ $pct }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted p-4">لا توجد عقود مسجلة بعد.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('admin_js')
<script>
    $(document).ready(function() {
        $('#reportPeriodSelect').on('change', function() {
            var val = $(this).val();
            if (val !== 'custom') {
                $('#reportDateFrom').val('');
                $('#reportDateTo').val('');
                $('#reportFilterForm').submit();
            }
        });

        $('#reportDateFrom, #reportDateTo').on('change', function() {
            $('#reportPeriodSelect').val('custom');
        });

        $('#reportCalculationMode').on('change', function() {
            $('#reportFilterForm').submit();
        });
    });
</script>
@endpush
