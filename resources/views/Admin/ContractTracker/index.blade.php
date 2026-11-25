@extends('layouts.admin.app')
@section('page_title') العقود السارية @endsection

@section('content')
    <x-list.card>
        <x-list.card-header name="العقود السارية" :add_button="true" :delete_button="true"></x-list.card-header>
        <x-list.card-body :responsive="true">
            <th class="text-white"><input type="checkbox" id="master"></th>
            <th class="text-white">كود المعاملة</th>
            <th class="text-white">رقم الجواز</th>
            <th class="text-white">اسم المتقدم</th>
            <th class="text-white">الوظيفة</th>
            <th class="text-white">المدينة</th>
            <th class="text-white">المرحلة الحالية</th>
            <th class="text-white">حالة المعاملة</th>
            <th class="text-white">الحالة</th>
            <th class="text-white">تحكم</th>
        </x-list.card-body>
    </x-list.card>

    <x-list.modal name="العقد الساري" :save_button="true" size="modal-lg"></x-list.modal>
@endsection

@push('admin_js')
    <script>
        var columns = [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'contract_code_badge', name: 'contract_code'},
            {data: 'passport_badge', name: 'passport_number'},
            {data: 'applicant_name', name: 'applicant_name'},
            {data: 'job_title', name: 'job_title'},
            {data: 'city_name', name: 'city_name'},
            {data: 'step_badge', name: 'step'},
            {data: 'status_text', name: 'status_text'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];

        $(document).ready(function () {
            var urlParams = new URLSearchParams(window.location.search);
            var searchParam = urlParams.get('search');
            if (searchParam) {
                setTimeout(function () {
                    var dt = $('#exportexample').DataTable();
                    if (dt) {
                        dt.search(searchParam).draw();
                    }
                }, 500);
            }
        });
    </script>
    @include('layouts.admin.inc.ajax', ['url' => 'contract-trackers'])
@endpush
