@extends('layouts.admin.app')
@section('page_title') طلبات التوظيف والعقود المستلمة @endsection

@section('content')
    <x-list.card>
        <x-list.card-header name="طلبات التوظيف والتقديم السريع" :add_button="true" :delete_button="true"></x-list.card-header>

        <!-- Top Filters -->
        <div class="row p-3 bg-light border-bottom mx-0 align-items-center">
            <div class="col-md-5 col-sm-6 mb-2 mb-md-0">
                <label class="small fw-bold text-dark mb-1"><i class="fa fa-filter text-primary mr-1"></i> تصفية حسب مرحلة / حالة الطلب:</label>
                <select id="filter_status" class="form-control form-control-solid">
                    <option value="">جميع الحالات والمراحل</option>
                    <option value="new">طلبات جديدة (واردة من الموقع)</option>
                    <option value="stage_1">المرحلة 1: قيد فحص الأوراق</option>
                    <option value="contracts">العقود المعتمدة (المرحلة 2 فما بعدها)</option>
                    <option value="contacted">تم التواصل هاتفياً / واتساب</option>
                    <option value="cancelled">الطلبات الملغاة فقط</option>
                </select>
            </div>
            <div class="col-md-5 col-sm-6 mb-2 mb-md-0">
                <label class="small fw-bold text-dark mb-1"><i class="fa fa-briefcase text-primary mr-1"></i> تصفية حسب فرصة العمل:</label>
                <select id="filter_job_id" class="form-control form-control-solid">
                    <option value="">جميع فرص العمل المتاحة</option>
                    @foreach($jobs as $job)
                        <option value="{{ $job->id }}">{{ $job->title }} ({{ $job->city ?? 'إيطاليا' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 col-sm-12 d-flex align-items-end pt-md-4">
                <button type="button" id="reset_filters" class="btn btn-outline-secondary btn-sm w-100">
                    <i class="fa fa-refresh mr-1"></i> إعادة الضبط
                </button>
            </div>
        </div>

        <x-list.card-body :responsive="true">
            <th class="text-white"><input type="checkbox" id="master"></th>
            <th class="text-white">كود الطلب</th>
            <th class="text-white">اسم المتقدم</th>
            <th class="text-white">رقم الهاتف / واتساب</th>
            <th class="text-white">العمر</th>
            <th class="text-white">المحافظة</th>
            <th class="text-white">الوظيفة المطلوبة</th>
            <th class="text-white">موقف الجواز</th>
            <th class="text-white">مرحلة الطلب</th>
            <th class="text-white">حالة العقد</th>
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
            {data: 'code_badge', name: 'code'},
            {data: 'name', name: 'name'},
            {data: 'phone_formatted', name: 'phone'},
            {data: 'age_formatted', name: 'age'},
            {data: 'city_name', name: 'city_name', orderable: false},
            {data: 'job_name', name: 'job_name', orderable: false},
            {data: 'passport_badge', name: 'passport_situation'},
            {data: 'status_badge', name: 'status_badge', orderable: false, searchable: false},
            {data: 'contract_badge', name: 'contract_badge', orderable: false, searchable: false},
            {data: 'created_at_formatted', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];

        $(document).ready(function () {
            // Attach filters to DataTable request via preXhr
            $('#exportexample').on('preXhr.dt', function (e, settings, data) {
                data.status = $('#filter_status').val();
                data.job_id = $('#filter_job_id').val();
            });

            // Trigger table reload when filters change
            $('#filter_status, #filter_job_id').on('change', function () {
                if ($.fn.DataTable.isDataTable('#exportexample')) {
                    $('#exportexample').DataTable().ajax.reload();
                }
            });

            // Reset filters
            $('#reset_filters').on('click', function () {
                $('#filter_status').val('');
                $('#filter_job_id').val('');
                if ($.fn.DataTable.isDataTable('#exportexample')) {
                    $('#exportexample').DataTable().ajax.reload();
                }
            });

            // Convert to contract handler
            $(document).on('click', '.convertContractBtn', function (e) {
                e.preventDefault();
                e.stopPropagation();

                var applicationId = $(this).data('id');
                var applicantName = $(this).data('name');
                var passportNumber = $(this).data('passport') || '';

                var swalInstance = window.Swal || window.swal;
                var convertUrl = '{{ route("job-applications.convertToContract", ":id") }}'.replace(':id', applicationId);

                if (swalInstance && typeof swalInstance.fire === 'function') {
                    swalInstance.fire({
                        title: "تحويل الطلب إلى عقد عمل موثق",
                        html: `
                            <p class="text-muted small">سيتم اعتماد الطلب ونقله إلى المرحلة الثانية (التسجيل بنظام فلوتسي) وإنشاء معاملة عقد رسمية للمتقدم: <strong>${applicantName}</strong>.</p>
                            <div class="form-group text-right" style="text-align: right; direction: rtl;">
                                <label class="small fw-bold">رقم جواز السفر (إجباري أو اختياري):</label>
                                <input type="text" id="swal_passport" class="form-control font-monospace dir-ltr" value="${passportNumber}" placeholder="مثال: A12345678">
                            </div>
                        `,
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: "#008c45",
                        cancelButtonColor: "#94a3b8",
                        confirmButtonText: "نعم، اعتماد وتحويل لعقد",
                        cancelButtonText: "إلغاء",
                        reverseButtons: true,
                        preConfirm: () => {
                            var passVal = document.getElementById('swal_passport') ? document.getElementById('swal_passport').value : '';
                            return { passport_number: passVal };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#global-loader').addClass('active');
                            $.ajax({
                                url: convertUrl,
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    passport_number: result.value.passport_number
                                },
                                success: function (data) {
                                    $('#global-loader').removeClass('active');
                                    my_toaster(data.message || 'تم تحويل الطلب إلى عقد بنجاح');
                                    if ($.fn.DataTable.isDataTable('#exportexample')) {
                                        $('#exportexample').DataTable().ajax.reload(null, false);
                                    }
                                },
                                error: function (data) {
                                    $('#global-loader').removeClass('active');
                                    handleAjaxError(data);
                                }
                            });
                        }
                    });
                }
            });
        });
    </script>
    @include('layouts.admin.inc.ajax', ['url' => 'job-applications'])
@endpush
