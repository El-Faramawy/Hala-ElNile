@extends('layouts.accounting.app')

@section('page_title')
    الحسابات المالية لعقود السفر
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <div class="card p-3 bg-light border-0">
                <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 10px;">
                    <div>
                        <h5 class="font-weight-bold text-dark mb-1"><i class="fe fe-briefcase ml-1 text-primary"></i> متابعة سداد عقود العمل بإيطاليا</h5>
                        <p class="text-muted mb-0 small">تسعير العقود باليورو، وتتبع ما تم تحصيله بالجنيه (وما يعادله باليورو)، وجدولة دفعات كل عقد على حدة.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-list.card>
        <x-list.card-header name="العقود المالية" :add_button="false" :delete_button="false"></x-list.card-header>
        <x-list.card-body :responsive="true">
            <th class="text-white">كود العقد</th>
            <th class="text-white">اسم المسافر</th>
            <th class="text-white">الوظيفة والمدينة</th>
            <th class="text-white">قيمة العقد باليورو</th>
            <th class="text-white">المحصل باليورو</th>
            <th class="text-white">المتبقي باليورو</th>
            <th class="text-white">نسبة السداد</th>
            <th class="text-white">إجراءات الحساب</th>
        </x-list.card-body>
    </x-list.card>

    <!-- Update Contract Price Modal -->
    <div class="modal fade" id="updatePriceModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 12px; border: none;">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold">تعديل قيمة العقد باليورو (€)</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updatePriceForm">
                    @csrf
                    <input type="hidden" id="priceContractId">
                    <div class="modal-body p-4">
                        <p class="font-weight-bold text-dark mb-2" id="priceApplicantName"></p>
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">إجمالي قيمة العقد باليورو (€) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" name="total_price_eur" id="modalTotalPriceEur" class="form-control font-weight-bold fs-16 text-primary" required>
                                <div class="input-group-append">
                                    <span class="input-group-text font-weight-bold">€</span>
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">المورد المرتبط بالعقد <small class="text-muted">(اختياري - مثال: د/ فايز)</small></label>
                            <select name="supplier_id" id="modalSupplierId" class="form-control">
                                <option value="">-- بدون تحديد مورد --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-0">
                            <label class="form-label font-weight-bold">تكلفة / مستحقات المورد من العقد (€) <small class="text-muted">(اختياري)</small></label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" name="supplier_cost_eur" id="modalSupplierCostEur" class="form-control font-weight-bold text-danger" placeholder="0.00">
                                <div class="input-group-append">
                                    <span class="input-group-text font-weight-bold">€</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary font-weight-bold" id="savePriceBtn">حفظ التعديل</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Quick Add Revenue Modal -->
    <div class="modal fade" id="quickRevenueModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 12px; border: none;">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fe fe-plus-circle ml-1"></i> تسجيل دفعة / سند قبض جديد للعقد</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="quickRevenueForm">
                    @csrf
                    <input type="hidden" name="contract_tracker_id" id="quickContractId">
                    <div class="modal-body p-4">
                        <div class="alert alert-light border mb-3">
                            <strong>المسافر:</strong> <span id="quickApplicantName" class="text-dark font-weight-bold"></span>
                        </div>

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
                                <select name="received_currency" id="quickCurrencySelect" class="form-control" required>
                                    <option value="EGP" selected>جنيه مصري (EGP)</option>
                                    <option value="EUR">يورو (€ EUR)</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">المبلغ المستلم <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0.01" name="amount" id="quickAmountInput" class="form-control font-weight-bold fs-16" required placeholder="0.00">
                            </div>

                            <div class="col-md-6 mb-3" id="quickEurEquivWrap">
                                <label class="form-label font-weight-bold">المقابل باليورو (€) <small class="text-muted">(يُخصم من دينه)</small></label>
                                <input type="number" step="0.01" min="0" name="eur_equivalent" id="quickEurEquivInput" class="form-control font-weight-bold text-success fs-16" placeholder="0.00">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">تاريخ المعاملة <span class="text-danger">*</span></label>
                                <input type="date" name="transaction_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">البيان / ملاحظات</label>
                                <input type="text" name="notes" class="form-control" placeholder="مثال: دفعة تأشيرة...">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-success font-weight-bold" id="saveQuickRevenueBtn">
                            <i class="fe fe-check ml-1"></i> حفظ سند القبض
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
            {data: 'code_badge', name: 'contract_code'},
            {data: 'applicant_name', name: 'applicant_name'},
            {data: 'job_city', name: 'job_title'},
            {data: 'total_price_formatted', name: 'total_price_eur'},
            {data: 'total_collected_formatted', name: 'id', orderable: false, searchable: false},
            {data: 'remaining_formatted', name: 'id', orderable: false, searchable: false},
            {data: 'progress_bar', name: 'id', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];

        // Navigate to Ledger
        $(document).on('click', '.show-contract-ledger-btn', function() {
            var contractId = $(this).data('id');
            window.location.href = "{{ url('accounting/contracts') }}/" + contractId + "/ledger";
        });

        // Open Update Contract Price Modal
        $(document).on('click', '.edit-contract-price-btn', function() {
            var contractId = $(this).data('id');
            var name = $(this).data('name');
            var total = $(this).data('total');
            var supplierId = $(this).data('supplier-id');
            var supplierCost = $(this).data('supplier-cost');
            $('#priceContractId').val(contractId);
            $('#priceApplicantName').text('المسافر: ' + name);
            $('#modalTotalPriceEur').val(total);
            $('#modalSupplierId').val(supplierId || '');
            $('#modalSupplierCostEur').val(supplierCost > 0 ? supplierCost : '');
            $('#updatePriceModal').modal('show');
        });

        $('#updatePriceForm').off('submit').on('submit', function(e) {
            e.preventDefault();
            var $form = $(this);
            if ($form.data('is_submitting')) return false;
            $form.data('is_submitting', true);

            var contractId = $('#priceContractId').val();
            var $btn = $('#savePriceBtn');
            $btn.prop('disabled', true);

            $.ajax({
                url: "{{ url('accounting/contracts') }}/" + contractId + "/price",
                type: 'POST',
                headers: { 'Accept': 'application/json' },
                data: $form.serialize(),
                success: function(res) {
                    $('#updatePriceModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'تم بنجاح',
                        text: res.message
                    });
                    if ($.fn.DataTable.isDataTable('#exportexample')) {
                        $('#exportexample').DataTable().ajax.reload(null, false);
                    }
                },
                error: function(xhr) {
                    var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'حدث خطأ أثناء تحديث السعر.';
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: msg
                    });
                },
                complete: function() {
                    $form.data('is_submitting', false);
                    $btn.prop('disabled', false);
                }
            });
        });

        // Quick Revenue Modal
        $(document).on('click', '.quick-add-revenue-btn', function() {
            var contractId = $(this).data('contract-id');
            var name = $(this).data('name');
            $('#quickContractId').val(contractId);
            $('#quickApplicantName').text(name);
            $('#quickRevenueForm')[0].reset();
            $('#quickContractId').val(contractId);
            $('#quickRevenueModal').modal('show');
        });

        $('#quickCurrencySelect').on('change', function() {
            if ($(this).val() === 'EUR') {
                $('#quickEurEquivWrap').addClass('d-none');
                $('#quickEurEquivInput').val('');
            } else {
                $('#quickEurEquivWrap').removeClass('d-none');
            }
        });

        $('#quickRevenueForm').on('submit', function(e) {
            e.preventDefault();
            var $btn = $('#saveQuickRevenueBtn');
            $btn.prop('disabled', true);

            $.ajax({
                url: "{{ route('revenues.store') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    $('#quickRevenueModal').modal('hide');
                    $btn.prop('disabled', false);
                    Swal.fire({
                        icon: 'success',
                        title: 'تم بنجاح',
                        text: res.message
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
    @include('layouts.admin.inc.ajax', ['url' => 'contracts'])
@endpush
