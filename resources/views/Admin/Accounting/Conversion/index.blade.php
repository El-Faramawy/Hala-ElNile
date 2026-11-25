@extends('layouts.accounting.app')

@section('page_title')
    سجل عمليات تحويل وتصريف العملة
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <div class="card p-3 bg-light border-0">
                <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 10px;">
                    <div>
                        <h5 class="font-weight-bold text-dark mb-1"><i class="fe fe-refresh-cw ml-1 text-primary"></i> محرك تحويل وتصريف النقدية المجمعة</h5>
                        <p class="text-muted mb-0 small">يتم تصريف الجنيه المصري المحصل من عدة إيرادات وتحويله إلى يورو، مع رصد وتسوية الفروقات آلياً.</p>
                    </div>
                    <div>
                        <a href="{{ route('revenues.index') }}" class="btn btn-primary font-weight-bold">
                            <i class="fe fe-check-square ml-1"></i> الانتقال لجدول المقبوضات وتحديد إيرادات للتحويل
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-list.card>
        <x-list.card-header name="عمليات تحويل العملة" :add_button="false" :delete_button="false"></x-list.card-header>
        <x-list.card-body :responsive="true">
            <th class="text-white">رقم العملية</th>
            <th class="text-white">تاريخ التحويل</th>
            <th class="text-white">إجمالي الجنيه المجمع</th>
            <th class="text-white">المفروض استلامه (€)</th>
            <th class="text-white">الفعلي المستلم (€)</th>
            <th class="text-white">الفارق المحسوب</th>
            <th class="text-white">الإجراء الآلي للفارق</th>
            <th class="text-white">خزينة اليورو المودع بها</th>
        </x-list.card-body>
    </x-list.card>
@endsection

@push('admin_js')
    <script>
        var columns = [
            {data: 'batch_no', name: 'id'},
            {data: 'formatted_date', name: 'conversion_date'},
            {data: 'total_egp_formatted', name: 'total_egp'},
            {data: 'expected_eur_formatted', name: 'expected_eur'},
            {data: 'actual_eur_formatted', name: 'actual_eur'},
            {data: 'diff_badge', name: 'diff_eur'},
            {data: 'result_details', name: 'diff_ref_id', orderable: false, searchable: false},
            {data: 'treasury_name', name: 'toTreasury.name'},
        ];
    </script>
    @include('layouts.admin.inc.ajax', ['url' => 'conversions'])
@endpush
