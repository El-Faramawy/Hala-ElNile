@extends('layouts.accounting.app')

@section('page_title')
    الموردين والوكلاء
@endsection

@section('content')
    <x-list.card>
        <x-list.card-header name="الموردين والوكلاء" :add_button="true" :delete_button="true"></x-list.card-header>
        <x-list.card-body :responsive="true">
            <th class="text-white"><input type="checkbox" id="master"></th>
            <th class="text-white">اسم المورد / الوكيل</th>
            <th class="text-white">رقم الهاتف</th>
            <th class="text-white">الحالة</th>
            <th class="text-white">تحكم</th>
        </x-list.card-body>
    </x-list.card>

    <x-list.modal name="المورد / الوكيل" :save_button="true"></x-list.modal>
@endsection

@push('admin_js')
    <script>
        var columns = [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'phone_formatted', name: 'phone'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
    </script>
    @include('layouts.admin.inc.ajax', ['url' => 'suppliers'])
@endpush
