@extends('layouts.accounting.app')

@section('page_title')
    أنواع وبنود المصروفات
@endsection

@section('content')
    <!-- Filter Bar -->
    <div class="card mb-3 border-0 shadow-sm" style="border-radius: 12px; background: #ffffff;">
        <div class="card-header bg-white py-2 px-4 d-flex flex-wrap justify-content-between align-items-center" style="border-radius: 12px; gap: 10px;">
            <div class="d-flex align-items-center" style="gap: 8px;">
                <span class="badge badge-light border text-primary p-2" style="border-radius: 8px;">
                    <i class="fe fe-filter fs-14"></i>
                </span>
                <span class="font-weight-bold text-dark fs-14">تصفية حسب التصنيف الرئيسي</span>
            </div>
            <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
                <div style="min-width: 240px;">
                    <select id="filter_type_category_id" class="form-control form-control-solid select2-filter w-100">
                        <option value="">جميع التصنيفات الرئيسية</option>
                        @foreach($expenseCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                        <option value="none">بنود عامة (بدون تصنيف)</option>
                    </select>
                </div>
                <button type="button" id="resetTypeFilterBtn" class="btn btn-outline-secondary btn-sm font-weight-bold py-2 px-3">
                    <i class="fe fe-rotate-ccw ml-1"></i> الكل
                </button>
                <a href="{{ route('expense-categories.index') }}" class="btn btn-outline-primary btn-sm font-weight-bold py-2 px-3">
                    <i class="fe fe-folder ml-1"></i> إدارة التصنيفات الرئيسية
                </a>
            </div>
        </div>
    </div>

    <!-- Standard DataTable Card -->
    <x-list.card>
        <x-list.card-header name="بنود المصروفات" :add_button="true" :delete_button="true"></x-list.card-header>
        <x-list.card-body :responsive="true">
            <th class="text-white"><input type="checkbox" id="master"></th>
            <th class="text-white">التصنيف الرئيسي</th>
            <th class="text-white">اسم البند</th>
            <th class="text-white">نوع البند</th>
            <th class="text-white">الحالة</th>
            <th class="text-white">تحكم</th>
        </x-list.card-body>
    </x-list.card>

    <x-list.modal name="بند المصروف" :save_button="true"></x-list.modal>
@endsection

@push('admin_js')
    <script>
        var columns = [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'main_category_badge', name: 'expenseCategory.name'},
            {data: 'name', name: 'name'},
            {data: 'is_other_badge', name: 'is_other'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];

        var originalDataFunc = function(d) {
            d.expense_category_id = $('#filter_type_category_id').val();
        };

        var typeReloadTimer = null;
        function reloadExpenseTypesTable() {
            clearTimeout(typeReloadTimer);
            typeReloadTimer = setTimeout(function() {
                if ($.fn.DataTable.isDataTable('#exportexample')) {
                    $('#exportexample').DataTable().ajax.reload();
                }
            }, 80);
        }

        $(document).ready(function() {
            $('#filter_type_category_id').off('change').on('change', function() {
                reloadExpenseTypesTable();
            });

            $('#resetTypeFilterBtn').off('click').on('click', function() {
                $('#filter_type_category_id').val('').trigger('change.select2');
                reloadExpenseTypesTable();
            });
        });
    </script>
    @include('layouts.admin.inc.ajax', ['url' => 'expense-types'])
@endpush
