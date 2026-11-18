@extends('layouts.admin.app')
@section('page_title') مسافرونا في إيطاليا @endsection

@section('content')
    <x-list.card>
        <x-list.card-header name="مسافرونا وقصص النجاح بإيطاليا" :add_button="true" :delete_button="true"></x-list.card-header>
        <x-list.card-body :responsive="true">
            <th class="text-white"><input type="checkbox" id="master"></th>
            <th class="text-white">الصورة الشخصية</th>
            <th class="text-white">صورة المعلم بإيطاليا</th>
            <th class="text-white">اسم المسافر</th>
            <th class="text-white">المحافظة</th>
            <th class="text-white">الوظيفة</th>
            <th class="text-white">الموقع / المعلم</th>
            <th class="text-white">شارة العقد</th>
            <th class="text-white">كود العقد</th>
            <th class="text-white">التقييم</th>
            <th class="text-white">الحالة</th>
            <th class="text-white">تحكم</th>
        </x-list.card-body>
    </x-list.card>

    <x-list.modal name="المسافر" :save_button="true" size="modal-lg"></x-list.modal>
@endsection

@push('admin_js')
    <script>
        var columns = [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'avatar_preview', name: 'avatar', orderable: false, searchable: false},
            {data: 'landmark_preview', name: 'landmark_image', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'governorate', name: 'governorate'},
            {data: 'job_title', name: 'job_title'},
            {data: 'location', name: 'location'},
            {data: 'badge', name: 'badge'},
            {data: 'contract_code', name: 'contract_code'},
            {data: 'rating_stars', name: 'rating', orderable: false, searchable: false},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
    </script>
    @include('layouts.admin.inc.ajax', ['url' => 'travelers'])
@endpush
