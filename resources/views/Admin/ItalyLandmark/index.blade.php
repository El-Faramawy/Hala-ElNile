@extends('layouts.admin.app')
@section('page_title')
    مناطق ومعالم إيطاليا
@endsection
@section('content')
    <x-list.card>
        <x-list.card-header name="مناطق ومعالم إيطاليا" :add_button="true" :delete_button="true"></x-list.card-header>
        <x-list.card-body :responsive="true">
            <th class="text-white"><input type="checkbox" id="master"></th>
            <th class="text-white">الصورة المعبرة</th>
            <th class="text-white">المدينة التابع لها</th>
            <th class="text-white">اسم المعلم / المنطقة</th>
            <th class="text-white">الترتيب</th>
            <th class="text-white">الحالة</th>
            <th class="text-white">تحكم</th>
        </x-list.card-body>
    </x-list.card>

    <x-list.modal name="معالم ومناطق إيطاليا" :save_button="true"></x-list.modal>
@endsection
@push('admin_js')
    <script>
        var columns = [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'image_view', name: 'image_view', orderable: false, searchable: false},
            {data: 'city_name', name: 'city_name'},
            {data: 'name', name: 'name'},
            {data: 'sort_order', name: 'sort_order'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
    </script>
    @include('layouts.admin.inc.ajax', ['url' => 'italy-landmarks'])
@endpush
