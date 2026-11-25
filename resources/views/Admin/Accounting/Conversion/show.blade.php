@extends('layouts.accounting.app')

@section('page_title')
    تفاصيل عملية التحويل #{{ $conversion->id }}
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 mb-3">
        <a href="{{ route('conversions.index') }}" class="btn btn-outline-primary btn-sm font-weight-bold">
            <i class="fe fe-arrow-right ml-1"></i> العودة لسجل التحويلات
        </a>
    </div>

    <!-- Summary Card -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title font-weight-bold text-white mb-0">بيانات التحويل #{{ $conversion->id }} - {{ $conversion->conversion_date?->format('Y-m-d') }}</h3>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-light rounded border">
                            <small class="text-muted font-weight-bold d-block">إجمالي الجنيه المجمع:</small>
                            <h4 class="font-weight-bold text-dark mb-0 mt-1">{{ number_format((float)$conversion->total_egp, 2) }} ج.م</h4>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-light rounded border">
                            <small class="text-muted font-weight-bold d-block">المفروض استلامه باليورو:</small>
                            <h4 class="font-weight-bold text-info mb-0 mt-1">{{ number_format((float)$conversion->expected_eur, 2) }} €</h4>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-light rounded border">
                            <small class="text-muted font-weight-bold d-block">المستلم الفعلي باليورو:</small>
                            <h4 class="font-weight-bold text-success mb-0 mt-1">{{ number_format((float)$conversion->actual_eur, 2) }} €</h4>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-light rounded border">
                            <small class="text-muted font-weight-bold d-block">حالة الفارق والتسوية:</small>
                            <h4 class="font-weight-bold mb-0 mt-1 {{ $conversion->diff_type === 'loss' ? 'text-danger' : ($conversion->diff_type === 'gain' ? 'text-success' : 'text-muted') }}">
                                {{ $conversion->diff_eur > 0 ? '+'.$conversion->diff_eur : $conversion->diff_eur }} €
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-light rounded border mt-2">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>خزينة الإيداع:</strong> <span class="badge badge-primary px-2 py-1">{{ $conversion->toTreasury?->name }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>ملاحظات:</strong> <span>{{ $conversion->notes ?? 'لا توجد ملاحظات إضافية' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Linked Revenues Table -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title font-weight-bold">الإيرادات وسندات القبض المندرجة في هذا التحويل</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th># السند</th>
                                <th>التاريخ</th>
                                <th>العميل / العقد</th>
                                <th>المبلغ بالمصري</th>
                                <th>المقابل باليورو</th>
                                <th>الخزينة الأصلية</th>
                                <th>إجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($conversion->revenues as $revenue)
                            <tr>
                                <td><span class="badge badge-default">#{{ $revenue->id }}</span></td>
                                <td>{{ $revenue->transaction_date?->format('Y-m-d') }}</td>
                                <td>
                                    <strong>{{ $revenue->contractTracker?->applicant_name ?? 'إيراد عام' }}</strong>
                                    @if($revenue->contractTracker)
                                        <br><small class="text-muted">كود: {{ $revenue->contractTracker->contract_code }}</small>
                                    @endif
                                </td>
                                <td class="font-weight-bold text-success">{{ number_format((float)$revenue->amount, 2) }} ج.م</td>
                                <td class="font-weight-bold text-primary">{{ number_format((float)$revenue->eur_equivalent, 2) }} €</td>
                                <td>{{ $revenue->treasury?->name }}</td>
                                <td>
                                    <a href="{{ route('revenues.receipt', $revenue->id) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="fa fa-print mr-1"></i> طباعة الإيصال
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
