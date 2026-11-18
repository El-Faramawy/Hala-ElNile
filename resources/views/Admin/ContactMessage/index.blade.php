@extends('layouts.admin.app')
@section('page_title') رسائل واستفسارات العملاء @endsection

@section('content')
    <x-list.card>
        <x-list.card-header name="رسائل واستفسارات العملاء" :add_button="false" :delete_button="true"></x-list.card-header>
        <x-list.card-body :responsive="true">
            <th class="text-white"><input type="checkbox" id="master"></th>
            <th class="text-white">الاسم</th>
            <th class="text-white">رقم الهاتف / واتساب</th>
            <th class="text-white">المحافظة</th>
            <th class="text-white">الموضوع</th>
            <th class="text-white">حالة القراءة</th>
            <th class="text-white">تاريخ الإرسال</th>
            <th class="text-white">تحكم</th>
        </x-list.card-body>
    </x-list.card>

    <x-list.modal name="تفاصيل الرسالة" :save_button="true" size="modal-lg"></x-list.modal>
@endsection

@push('admin_js')
    <script>
        var columns = [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'phone', name: 'phone'},
            {data: 'city_name', name: 'city_name', orderable: false},
            {data: 'subject', name: 'subject'},
            {data: 'read_status', name: 'read_status', orderable: false, searchable: false},
            {data: 'created_at_formatted', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];

        $(document).on('click', '.toggle-read-btn', function (e) {
            e.preventDefault();
            var btn = $(this);
            var id = btn.data('id');
            var url = "{{ url('admin/contact-messages') }}/" + id + "/toggle-read";

            btn.prop('disabled', true);

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function (res) {
                    if (typeof my_toaster === 'function') {
                        my_toaster(res.message || 'تم تحديث حالة الرسالة بنجاح', 'success');
                    }
                    if ($.fn.DataTable.isDataTable('#exportexample')) {
                        $('#exportexample').DataTable().ajax.reload(null, false);
                    }
                },
                error: function () {
                    if (typeof my_toaster === 'function') {
                        my_toaster('حدث خطأ أثناء تحديث حالة الرسالة', 'error');
                    }
                    btn.prop('disabled', false);
                }
            });
        });
    </script>
    @include('layouts.admin.inc.ajax', ['url' => 'contact-messages'])
@endpush
