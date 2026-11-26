@extends('layouts.accounting.app')

@section('page_title')
    تصنيفات المصروفات الرئيسية
@endsection

@section('content')
    <x-list.card>
        <x-list.card-header name="التصنيفات الرئيسية للمصروفات" :add_button="true" :delete_button="true"></x-list.card-header>
        <x-list.card-body :responsive="true">
            <th class="text-white"><input type="checkbox" id="master"></th>
            <th class="text-white">اسم التصنيف الرئيسي</th>
            <th class="text-white">عدد البنود الفرعية</th>
            <th class="text-white">الحالة</th>
            <th class="text-white">تحكم</th>
        </x-list.card-body>
    </x-list.card>

    <x-list.modal name="التصنيف الرئيسي للمصروفات" :save_button="true"></x-list.modal>
@endsection

@push('admin_js')
    <script>
        var columns = [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'types_count', name: 'expense_types_count', searchable: false},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
    </script>
    @include('layouts.admin.inc.ajax', ['url' => 'expense-categories'])
@endpush
