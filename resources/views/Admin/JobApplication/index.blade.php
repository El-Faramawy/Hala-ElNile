@extends('layouts.admin.app')
@section('page_title') طلبات التوظيف المستلمة @endsection

@section('content')
    <x-list.card>
        <x-list.card-header name="طلبات التوظيف والتقديم السريع" :add_button="false" :delete_button="true"></x-list.card-header>
        <x-list.card-body :responsive="true">
            <th class="text-white"><input type="checkbox" id="master"></th>
            <th class="text-white">اسم المتقدم</th>
            <th class="text-white">رقم الهاتف / واتساب</th>
            <th class="text-white">العمر</th>
            <th class="text-white">المحافظة</th>
            <th class="text-white">الوظيفة المطلوبة</th>
            <th class="text-white">موقف الجواز</th>
            <th class="text-white">الحالة</th>
            <th class="text-white">تاريخ التقديم</th>
            <th class="text-white">تحكم</th>
        </x-list.card-body>
    </x-list.card>

    <x-list.modal name="طلب التوظيف" :save_button="true" size="modal-lg"></x-list.modal>
@endsection

@push('admin_js')
    <script>
        var columns = [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'phone', name: 'phone'},
            {data: 'age', name: 'age'},
            {data: 'city_name', name: 'city_name', orderable: false},
            {data: 'job_name', name: 'job_name', orderable: false},
            {data: 'passport_situation', name: 'passport_situation'},
            {data: 'status_badge', name: 'status_badge', orderable: false, searchable: false},
            {data: 'created_at_formatted', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
    </script>
    @include('layouts.admin.inc.ajax', ['url' => 'job-applications'])
@endpush
