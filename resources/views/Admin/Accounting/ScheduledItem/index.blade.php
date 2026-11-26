@extends('layouts.accounting.app')

@section('page_title')
    الدفعات والإيرادات المجدولة
@endsection

@section('content')
    @if(($dueSoonSummary['count'] ?? 0) > 0)
        <div class="alert alert-warning d-flex flex-wrap align-items-center justify-content-between shadow-sm mb-3" style="border-radius: 10px; gap: 10px;">
            <div class="d-flex align-items-center" style="gap: 10px;">
                <i class="fe fe-bell fs-20 text-danger"></i>
                <div>
                    <strong>تنبيه استحقاق قريب:</strong>
                    يوجد <strong>{{ $dueSoonSummary['count'] }}</strong> بنود مجدولة مستحقة خلال 7 أيام أو متأخرة
                    @if(! empty($dueSoonSummary['earliest_date']))
                        — <span>أقرب تاريخ للدفع/التحصيل القادم: <strong class="text-danger">{{ $dueSoonSummary['earliest_date'] }}</strong></span>
                    @endif
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-danger font-weight-bold" id="filterDueSoonBtn">
                <i class="fe fe-filter ml-1"></i> عرض المستحق قريباً فقط
            </button>
        </div>
    @endif

    <!-- Filter Bar -->
    <div class="card mb-3 border-0 shadow-sm" style="border-radius: 12px; background: #ffffff;">
        <div class="card-header bg-white py-2 px-4 d-flex justify-content-between align-items-center" style="border-radius: 12px; cursor: pointer;" data-toggle="collapse" data-target="#scheduledFilterCollapse" aria-expanded="false">
            <div class="d-flex align-items-center" style="gap: 8px;">
                <span class="badge badge-light border text-primary p-2" style="border-radius: 8px;">
                    <i class="fe fe-sliders fs-14"></i>
                </span>
                <span class="font-weight-bold text-dark fs-14">تصفية الدفعات والإيرادات المجدولة</span>
            </div>
            <button class="btn btn-sm btn-outline-primary font-weight-bold" type="button">
                <i class="fe fe-filter ml-1"></i> خيارات الفلترة <i class="fa fa-chevron-down mr-1"></i>
            </button>
        </div>

        <div class="card-body p-3 p-md-4 collapse border-top" id="scheduledFilterCollapse">
            <form id="scheduledFilterForm">
                <div class="row align-items-end">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                        <label class="form-label font-weight-bold fs-12 text-muted mb-1">النوع:</label>
                        <select name="type" id="filter_sched_type" class="form-control">
                            <option value="">الكل (مصروفات وإيرادات)</option>
                            <option value="expense">مصروف مجدول</option>
                            <option value="revenue">إيراد مجدول</option>
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                        <label class="form-label font-weight-bold fs-12 text-muted mb-1">التكرار:</label>
                        <select name="frequency" id="filter_sched_frequency" class="form-control">
                            <option value="">جميع التكرارات</option>
                            <option value="monthly">شهري (كل شهر)</option>
                            <option value="yearly">سنوي (كل سنة)</option>
                            <option value="once">تاريخ محدد (مرة واحدة)</option>
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                        <label class="form-label font-weight-bold fs-12 text-muted mb-1">حالة الاستحقاق:</label>
                        <select name="due_status" id="filter_sched_due_status" class="form-control">
                            <option value="">الكل</option>
                            <option value="due_soon">قريب الاستحقاق (خلال أسبوع) / متأخر</option>
                            <option value="completed">مكتمل / متوقف</option>
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-12 col-lg-3 mb-2">
                        <div class="d-flex" style="gap: 8px;">
                            <button type="button" id="schedResetBtn" class="btn btn-outline-secondary font-weight-bold flex-fill">
                                <i class="fe fe-rotate-ccw ml-1"></i> إعادة ضبط
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Card -->
    <x-list.card>
        <x-list.card-header name="جدول الدفعات والإيرادات المجدولة" :add_button="true" :delete_button="true"></x-list.card-header>
        <x-list.card-body :responsive="true">
            <th class="text-white"><input type="checkbox" id="master"></th>
            <th class="text-white">البيان / العنوان</th>
            <th class="text-white">النوع</th>
            <th class="text-white">التكرار</th>
            <th class="text-white">المبلغ</th>
            <th class="text-white">تاريخ الدفع/التحصيل القادم</th>
            <th class="text-white">التفاصيل والخزينة</th>
            <th class="text-white">تحكم</th>
        </x-list.card-body>
    </x-list.card>

    <x-list.modal name="بند مجدول" :save_button="true" :save_and_print="true"></x-list.modal>
@endsection

@push('admin_js')
    <script>
        var columns = [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'title', name: 'title'},
            {data: 'type_badge', name: 'type'},
            {data: 'frequency_badge', name: 'frequency'},
            {data: 'amount_formatted', name: 'amount'},
            {data: 'due_date_badge', name: 'next_due_date'},
            {data: 'party_details', name: 'party_details', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];

        var originalDataFunc = function(d) {
            d.type = $('#filter_sched_type').val();
            d.frequency = $('#filter_sched_frequency').val();
            d.due_status = $('#filter_sched_due_status').val();
        };

        var schedReloadTimer = null;
        function reloadScheduledTable() {
            clearTimeout(schedReloadTimer);
            schedReloadTimer = setTimeout(function() {
                if ($.fn.DataTable.isDataTable('#exportexample')) {
                    $('#exportexample').DataTable().ajax.reload();
                }
            }, 80);
        }

        $(document).ready(function() {
            $('#scheduledFilterForm select').off('change').on('change', function() {
                reloadScheduledTable();
            });

            $('#schedResetBtn').off('click').on('click', function() {
                $('#scheduledFilterForm')[0].reset();
                reloadScheduledTable();
            });

            $('#filterDueSoonBtn').on('click', function() {
                $('#filter_sched_due_status').val('due_soon').trigger('change');
            });

            $(document).on('click', '#addBtn', function() {
                window.reloadAfterModalSave = true;
                $('#Modal .modal-header h2').text('إضافة بند مجدول');
            });

            $(document).on('click', '.editBtn', function() {
                window.reloadAfterModalSave = true;
                $('#Modal .modal-header h2').text('تعديل بند مجدول');
            });

            // Open full Expense or Revenue create modal pre-filled with the scheduled item's details
            $(document).on('click', '.process-scheduled-btn', function() {
                var id = $(this).data('id');
                var type = $(this).data('type');
                var url = type === 'revenue'
                    ? "{{ route('revenues.create') }}?scheduled_item_id=" + id
                    : "{{ route('expenses.create') }}?scheduled_item_id=" + id;

                window.reloadAfterModalSave = true;
                $('#Modal .modal-header h2').text(type === 'revenue' ? 'تسجيل تحصيل إيراد مجدول' : 'تسجيل سداد مصروف مجدول');
                $('#Modal').modal('show');
                $('#form-load').html('<div class="text-center p-4"><i class="fa fa-spinner fa-spin fa-2x text-primary"></i></div>');
                $('#form-load').load(url, function() {
                    if (typeof initModalPlugins === 'function') {
                        initModalPlugins();
                    }
                });
            });
        });
    </script>
    @include('layouts.admin.inc.ajax', ['url' => 'scheduled-items'])
@endpush
