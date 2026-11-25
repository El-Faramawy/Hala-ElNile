@extends('layouts.accounting.app')

@section('page_title')
    المصروفات وسندات الصرف
@endsection

@section('content')
    <!-- Enhanced Responsive Filter Bar (Collapsible Dropdown) -->
    <div class="card mb-3 border-0 shadow-sm" style="border-radius: 12px; background: #ffffff;">
        <div class="card-header bg-white py-2 px-4 d-flex justify-content-between align-items-center" style="border-radius: 12px; cursor: pointer;" data-toggle="collapse" data-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse" id="filterCollapseHeader">
            <div class="d-flex align-items-center" style="gap: 8px;">
                <span class="badge badge-light border text-primary p-2" style="border-radius: 8px;">
                    <i class="fe fe-sliders fs-14"></i>
                </span>
                <span class="font-weight-bold text-dark fs-14">تصفية وفلترة المصروفات المتقدمة</span>
                <span class="badge badge-primary px-2 py-1 fs-11 d-none" id="activeFilterBadge">فلاتر مفعّلة</span>
            </div>
            <button class="btn btn-sm btn-outline-primary font-weight-bold" type="button">
                <i class="fe fe-filter ml-1"></i> خيارات الفلترة <i class="fa fa-chevron-down mr-1"></i>
            </button>
        </div>

        <div class="card-body p-3 p-md-4 collapse border-top" id="filterCollapse">
            <form id="expenseFilterForm">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-3">
                        <label class="form-label font-weight-bold fs-12 text-muted mb-1">
                            <i class="fe fe-tag text-primary ml-1"></i> بند المصروف:
                        </label>
                        <select name="expense_type_id" id="filter_expense_type_id" class="form-control form-control-solid select2-filter w-100">
                            <option value="">جميع بنود المصروفات</option>
                            @foreach($expenseTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-3">
                        <label class="form-label font-weight-bold fs-12 text-muted mb-1">
                            <i class="fe fe-database text-info ml-1"></i> الخزينة المنصرف منها:
                        </label>
                        <select name="treasury_id" id="filter_treasury_id" class="form-control form-control-solid select2-filter w-100">
                            <option value="">جميع الخزائن والحسابات</option>
                            @foreach($treasuries as $treasury)
                                <option value="{{ $treasury->id }}">{{ $treasury->name }} ({{ $treasury->currency === 'EUR' ? '€ يورو' : 'ج.م' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-3">
                        <label class="form-label font-weight-bold fs-12 text-muted mb-1">
                            <i class="fe fe-dollar-sign text-success ml-1"></i> العملة:
                        </label>
                        <select name="currency" id="filter_currency" class="form-control form-control-solid select2-filter w-100">
                            <option value="">جميع العملات</option>
                            <option value="EGP">جنيه مصري (EGP)</option>
                            <option value="EUR">يورو (€ EUR)</option>
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-3">
                        <label class="form-label font-weight-bold fs-12 text-muted mb-1">
                            <i class="fe fe-user text-warning ml-1"></i> الموظف:
                        </label>
                        <select name="admin_id" id="filter_admin_id" class="form-control form-control-solid select2-filter w-100">
                            <option value="">جميع الموظفين</option>
                            @foreach($admins as $adminUser)
                                <option value="{{ $adminUser->id }}">{{ $adminUser->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-3">
                        <label class="form-label font-weight-bold fs-12 text-muted mb-1">
                            <i class="fe fe-truck text-secondary ml-1"></i> المورد:
                        </label>
                        <select name="supplier_id" id="filter_supplier_id" class="form-control form-control-solid select2-filter w-100">
                            <option value="">جميع الموردين</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-3">
                        <label class="form-label font-weight-bold fs-12 text-muted mb-1">
                            <i class="fe fe-calendar text-primary ml-1"></i> من تاريخ الصرف:
                        </label>
                        <input type="date" name="date_from" id="filter_date_from" class="form-control form-control-solid">
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-3">
                        <label class="form-label font-weight-bold fs-12 text-muted mb-1">
                            <i class="fe fe-calendar text-primary ml-1"></i> إلى تاريخ الصرف:
                        </label>
                        <input type="date" name="date_to" id="filter_date_to" class="form-control form-control-solid">
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-3">
                        <label class="form-label font-weight-bold fs-12 text-muted mb-1">
                            <i class="fe fe-search text-info ml-1"></i> بحث بالبيان أو رقم السند:
                        </label>
                        <input type="text" name="search_text" id="filter_search_text" class="form-control form-control-solid" placeholder="اكتب للبحث السريع...">
                    </div>
                </div>

                <div class="row pt-2 border-top">
                    <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4 mr-auto">
                        <div class="d-flex flex-wrap" style="gap: 8px;">
                            <button type="button" id="filterBtn" class="btn btn-primary font-weight-bold flex-fill py-2">
                                <i class="fe fe-filter ml-1"></i> تطبيق الفلتر
                            </button>
                            <button type="button" id="resetFilterBtn" class="btn btn-outline-secondary font-weight-bold py-2 px-3" title="إعادة ضبط الفلاتر">
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
        <x-list.card-header name="سندات الصرف والمصروفات" :add_button="true" :delete_button="true"></x-list.card-header>
        <x-list.card-body :responsive="true">
            <th class="text-white"><input type="checkbox" id="master"></th>
            <th class="text-white">رقم السند</th>
            <th class="text-white">التاريخ</th>
            <th class="text-white">بند المصروف</th>
            <th class="text-white">المبلغ المنصرف</th>
            <th class="text-white">الجهة / المستفيد</th>
            <th class="text-white">الخزينة المنصرف منها</th>
            <th class="text-white">صورة الوصل</th>
            <th class="text-white">تحكم</th>
        </x-list.card-body>
    </x-list.card>

    <!-- Create/Edit Modal with Save & Print Button -->
    <x-list.modal name="سند الصرف" :save_button="true" :save_and_print="true"></x-list.modal>
@endsection

@push('admin_js')
    <script>
        var columns = [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'voucher_no', name: 'id'},
            {data: 'formatted_date', name: 'transaction_date'},
            {data: 'type_badge', name: 'expenseType.name'},
            {data: 'amount_formatted', name: 'amount'},
            {data: 'beneficiary', name: 'admin.name'},
            {data: 'treasury_name', name: 'treasury.name'},
            {data: 'image', name: 'image', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];

        // Send filter parameters via originalDataFunc
        var originalDataFunc = function(d) {
            d.expense_type_id = $('#filter_expense_type_id').val();
            d.treasury_id = $('#filter_treasury_id').val();
            d.admin_id = $('#filter_admin_id').val();
            d.supplier_id = $('#filter_supplier_id').val();
            d.currency = $('#filter_currency').val();
            d.date_from = $('#filter_date_from').val();
            d.date_to = $('#filter_date_to').val();
            d.search_text = $('#filter_search_text').val();
        };

        function updateActiveFilterBadge() {
            var activeCount = 0;
            if ($('#filter_expense_type_id').val()) activeCount++;
            if ($('#filter_treasury_id').val()) activeCount++;
            if ($('#filter_admin_id').val()) activeCount++;
            if ($('#filter_supplier_id').val()) activeCount++;
            if ($('#filter_currency').val()) activeCount++;
            if ($('#filter_date_from').val()) activeCount++;
            if ($('#filter_date_to').val()) activeCount++;
            if ($('#filter_search_text').val()) activeCount++;

            if (activeCount > 0) {
                $('#activeFilterBadge').text(activeCount + ' فلاتر نشطة').removeClass('d-none');
            } else {
                $('#activeFilterBadge').addClass('d-none');
            }
        }

        var expenseReloadTimer = null;
        function reloadExpensesTable() {
            updateActiveFilterBadge();
            clearTimeout(expenseReloadTimer);
            expenseReloadTimer = setTimeout(function() {
                if ($.fn.DataTable.isDataTable('#exportexample')) {
                    $('#exportexample').DataTable().ajax.reload();
                } else if (window.table && typeof window.table.draw === 'function') {
                    window.table.draw();
                }
            }, 80);
        }

        $(document).ready(function() {
            // Filter button click
            $('#filterBtn').off('click').on('click', function(e) {
                e.preventDefault();
                reloadExpensesTable();
            });

            // Reset button click
            $('#resetFilterBtn').off('click').on('click', function(e) {
                e.preventDefault();
                $('#expenseFilterForm')[0].reset();
                if ($.fn.select2) {
                    $('#expenseFilterForm select').val('').trigger('change.select2');
                }
                reloadExpensesTable();
            });

            // Enter key on inputs triggers filter
            $('#expenseFilterForm input').off('keypress').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    reloadExpensesTable();
                }
            });

            // Auto-reload when dropdown filter changes (single debounced request)
            $('#expenseFilterForm select').off('change').on('change', function() {
                reloadExpensesTable();
            });
        });
    </script>
    @include('layouts.admin.inc.ajax', ['url' => 'expenses'])
@endpush
