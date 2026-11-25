@extends('layouts.admin.app')
@section('page_title') فرص العمل المتاحة @endsection

@section('content')
    <x-list.card>
        <x-list.card-header name="فرص العمل المتاحة" :add_button="true" :delete_button="true"></x-list.card-header>
        <x-list.card-body :responsive="true">
            <th class="text-white"><input type="checkbox" id="master"></th>
            <th class="text-white">الصورة</th>
            <th class="text-white">المسمى الوظيفي</th>
            <th class="text-white">القسم</th>
            <th class="text-white">المدينة بإيطاليا</th>
            <th class="text-white">الراتب المتوقع</th>
            <th class="text-white">نوع العقد</th>
            <th class="text-white">الحالة</th>
            <th class="text-white">تحكم</th>
        </x-list.card-body>
    </x-list.card>

    <x-list.modal name="فرصة العمل المتاحة" :save_button="true" size="modal-xl"></x-list.modal>
@endsection

@push('admin_js')
    <script>
        var columns = [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'image', name: 'image', orderable: false, searchable: false},
            {data: 'title', name: 'title'},
            {data: 'category', name: 'category', orderable: false},
            {data: 'city', name: 'city'},
            {data: 'salary_text', name: 'salary_text'},
            {data: 'contract_type', name: 'contract_type'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
    </script>
    @include('layouts.admin.inc.ajax', ['url' => 'jobs'])
@endpush
