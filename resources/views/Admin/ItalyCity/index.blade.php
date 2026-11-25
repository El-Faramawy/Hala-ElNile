@extends('layouts.admin.app')
@section('page_title')
    مدن إيطاليا
@endsection
@section('content')
    <x-list.card>
        <x-list.card-header name="مدن إيطاليا" :add_button="true" :delete_button="true"></x-list.card-header>
        <x-list.card-body :responsive="true">
            <th class="text-white"><input type="checkbox" id="master"></th>
            <th class="text-white">الصورة</th>
            <th class="text-white">اسم المدينة بإيطاليا</th>
            <th class="text-white">عدد المعالم المسجلة</th>
            <th class="text-white">الترتيب</th>
            <th class="text-white">الحالة</th>
            <th class="text-white">تحكم</th>
        </x-list.card-body>
    </x-list.card>

    <x-list.modal name="مدن إيطاليا" :save_button="true"></x-list.modal>
@endsection
@push('admin_js')
    <script>
        var columns = [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'image_view', name: 'image_view', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'landmarks_count_badge', name: 'landmarks_count_badge', orderable: false, searchable: false},
            {data: 'sort_order', name: 'sort_order'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
    </script>
    @include('layouts.admin.inc.ajax', ['url' => 'italy-cities'])
@endpush
