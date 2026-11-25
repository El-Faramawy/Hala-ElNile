@extends('layouts.accounting.app')

@section('page_title')
    الدفعات المتأخرة ومستحقات التحصيل
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <div class="card p-3 bg-danger-transparent border-danger">
                <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 10px;">
                    <div>
                        <h5 class="font-weight-bold text-danger mb-1"><i class="fe fe-alert-triangle ml-1"></i> شاشة المتابعة والتحصيل السريع</h5>
                        <p class="text-dark mb-0 small">تظهر هنا جميع الدفعات المجدولة على عقود السفر التي حل موعد استحقاقها ولم يتم سدادها بعد. يمكنك تذكير المسافر بنقرة زر عبر واتساب أو تسجيل سند قبض فوري.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-list.card>
        <x-list.card-header name="الدفعات المتأخرة" :add_button="false" :delete_button="false"></x-list.card-header>
        <x-list.card-body :responsive="true">
            <th class="text-white">كود العقد</th>
            <th class="text-white">اسم المسافر</th>
            <th class="text-white">التواصل السريع</th>
            <th class="text-white">مسمى الدفعة</th>
            <th class="text-white">المبلغ المستحق (€)</th>
            <th class="text-white">تاريخ الاستحقاق</th>
            <th class="text-white">مدة التأخير</th>
            <th class="text-white">إجراء التحصيل</th>
        </x-list.card-body>
    </x-list.card>

    <!-- Quick Collect Modal -->
    <div class="modal fade" id="collectModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 12px; border: none;">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fe fe-dollar-sign ml-1"></i> تسجيل سند قبض للدفعة المستحقة</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="collectForm">
                    @csrf
                    <input type="hidden" name="contract_tracker_id" id="collectContractId">
                    <input type="hidden" name="installment_id" id="collectInstallmentId">

                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">الخزينة المستلمة <span class="text-danger">*</span></label>
                                <select name="treasury_id" class="form-control" required>
                                    @foreach($treasuries as $treasury)
                                        <option value="{{ $treasury->id }}" data-currency="{{ $treasury->currency }}">
                                            {{ $treasury->name }} ({{ $treasury->currency }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">العملة المستلمة <span class="text-danger">*</span></label>
                                <select name="received_currency" id="collectCurrencySelect" class="form-control" required>
                                    <option value="EGP" selected>جنيه مصري (EGP)</option>
                                    <option value="EUR">يورو (€ EUR)</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">المبلغ المسدد <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0.01" name="amount" id="collectAmountInput" class="form-control font-weight-bold fs-16" required placeholder="0.00">
                            </div>

                            <div class="col-md-6 mb-3" id="collectEurEquivWrap">
                                <label class="form-label font-weight-bold">المقابل باليورو (€) <small class="text-muted">(تُخصم من دينه)</small></label>
                                <input type="number" step="0.01" min="0" name="eur_equivalent" id="collectEurEquivInput" class="form-control font-weight-bold text-success fs-16" placeholder="0.00">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">تاريخ السداد <span class="text-danger">*</span></label>
                                <input type="date" name="transaction_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">البيان والملاحظات</label>
                                <input type="text" name="notes" class="form-control" value="سداد دفعة مستحقة">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-success font-weight-bold" id="saveCollectBtn">
                            <i class="fe fe-check ml-1"></i> تأكيد السداد وقبض المبلغ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('admin_js')
    <script>
        var columns = [
            {data: 'contract_code', name: 'contractTracker.contract_code'},
            {data: 'applicant_name', name: 'contractTracker.applicant_name'},
            {data: 'phone_action', name: 'id', orderable: false, searchable: false},
            {data: 'title', name: 'title'},
            {data: 'formatted_amount', name: 'amount_eur'},
            {data: 'formatted_due_date', name: 'due_date'},
            {data: 'overdue_badge', name: 'due_date'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];

        $(document).on('click', '.collect-installment-btn', function() {
            var installmentId = $(this).data('id');
            var contractId = $(this).data('contract-id');
            var amountEur = $(this).data('amount-eur');

            $('#collectInstallmentId').val(installmentId);
            $('#collectContractId').val(contractId);
            $('#collectEurEquivInput').val(amountEur);
            $('#collectModal').modal('show');
        });

        $('#collectCurrencySelect').on('change', function() {
            if ($(this).val() === 'EUR') {
                $('#collectEurEquivWrap').addClass('d-none');
                $('#collectAmountInput').val($('#collectEurEquivInput').val());
            } else {
                $('#collectEurEquivWrap').removeClass('d-none');
            }
        });

        $('#collectForm').on('submit', function(e) {
            e.preventDefault();
            var $btn = $('#saveCollectBtn');
            var installmentId = $('#collectInstallmentId').val();
            $btn.prop('disabled', true);

            $.ajax({
                url: "{{ route('revenues.store') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    // Also mark installment as paid
                    if (installmentId) {
                        $.ajax({
                            url: "{{ url('accounting/overdue') }}/" + installmentId + "/mark-paid",
                            type: 'POST',
                            data: { _token: "{{ csrf_token() }}" }
                        });
                    }

                    $('#collectModal').modal('hide');
                    $btn.prop('disabled', false);
                    Swal.fire({
                        icon: 'success',
                        title: 'تم التحصيل بنجاح',
                        text: 'تم إنشاء سند القبض واعتبار الدفعة مسددة.'
                    });
                    window.table.draw();
                },
                error: function(xhr) {
                    $btn.prop('disabled', false);
                    var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'حدث خطأ أثناء حفظ الإيراد.';
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: msg
                    });
                }
            });
        });
    </script>
    @include('layouts.admin.inc.ajax', ['url' => 'overdue'])
@endpush
