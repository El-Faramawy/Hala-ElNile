<form action="{{ route('expenses.store') }}" method="POST" id="form" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <!-- Expense Type -->
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">نوع / بند المصروف <span class="text-danger">*</span></label>
            <select name="expense_type_id" id="expenseTypeSelect" class="form-control select2-basic" required>
                @foreach($expenseTypes as $type)
                    <option value="{{ $type->id }}" data-is-other="{{ $type->is_other ? '1' : '0' }}">
                        {{ $type->name }} {{ $type->is_other ? '(يتطلب توضيح)' : '' }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Treasury -->
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">الخزينة المنصرف منها <span class="text-danger">*</span></label>
            <select name="treasury_id" class="form-control select2-basic" id="expenseTreasurySelect" required>
                @foreach($treasuries as $treasury)
                    <option value="{{ $treasury->id }}" data-currency="{{ $treasury->currency }}">
                        {{ $treasury->name }} (رصيدها: {{ number_format((float)$treasury->balance, 2) }} {{ $treasury->currency }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Currency -->
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">عملة الصرف <span class="text-danger">*</span></label>
            <select name="currency" id="expenseCurrencySelect" class="form-control" required>
                <option value="EGP" selected>جنيه مصري (EGP)</option>
                <option value="EUR">يورو (€ EUR)</option>
            </select>
        </div>

        <!-- Amount -->
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">المبلغ المنصرف <span class="text-danger">*</span></label>
            <div class="input-group">
                <input type="number" step="0.01" min="0.01" name="amount" class="form-control font-weight-bold text-danger fs-16" placeholder="0.00" required>
                <div class="input-group-append">
                    <span class="input-group-text font-weight-bold" id="expenseCurrencySymbol">ج.م</span>
                </div>
            </div>
        </div>

        <!-- Other Details (Mandatory when 'other' is chosen) -->
        <div class="col-md-12 mb-3 d-none" id="otherDetailsWrap">
            <div class="p-3 bg-light rounded border border-warning">
                <label class="form-label font-weight-bold text-dark">
                    <i class="fe fe-alert-circle text-warning ml-1"></i> بيان وتفاصيل المصروف <span class="text-danger">* (إجباري عند اختيار أخرى)</span>
                </label>
                <input type="text" name="other_details" id="otherDetailsInput" class="form-control font-weight-semibold" placeholder="اكتب بالتفصيل في ماذا تم صرف هذا المبلغ...">
            </div>
        </div>

        <!-- Beneficiary Type -->
        <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold">المستفيد من المصروف</label>
            <div class="d-flex align-items-center mb-2" style="gap: 20px;">
                <label class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" name="expense_beneficiary" value="none" checked>
                    <span class="custom-control-label font-weight-bold">مصروف عام</span>
                </label>
                <label class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" name="expense_beneficiary" value="admin">
                    <span class="custom-control-label font-weight-bold">موظف (راتب / سلفة)</span>
                </label>
                <label class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" name="expense_beneficiary" value="supplier">
                    <span class="custom-control-label font-weight-bold">مورد / جهة خارجية</span>
                </label>
                <label class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" name="expense_beneficiary" value="contract">
                    <span class="custom-control-label font-weight-bold">مصروف مباشر لعقد</span>
                </label>
            </div>
        </div>

        <!-- Admin Select -->
        <div class="col-md-12 mb-3 d-none" id="adminSelectWrap">
            <label class="form-label font-weight-bold">اختيار الموظف</label>
            <select name="admin_id" class="form-control select2-basic" id="adminSelect">
                <option value="">-- اختر الموظف --</option>
                @foreach($admins as $adminUser)
                    <option value="{{ $adminUser->id }}">{{ $adminUser->name }} ({{ $adminUser->phone ?? 'بدون هاتف' }})</option>
                @endforeach
            </select>
        </div>

        <!-- Supplier Select -->
        <div class="col-md-12 mb-3 d-none" id="supplierSelectWrap">
            <label class="form-label font-weight-bold">اختيار المورد</label>
            <select name="supplier_id" class="form-control select2-basic" id="supplierSelect">
                <option value="">-- اختر المورد --</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Contract Select -->
        <div class="col-md-12 mb-3 d-none" id="contractSelectWrap">
            <label class="form-label font-weight-bold">اختيار العقد المرتبط</label>
            <select name="contract_tracker_id" class="form-control select2-basic" id="contractSelect">
                <option value="">-- اختر العقد --</option>
                @foreach($contracts as $contract)
                    <option value="{{ $contract->id }}">{{ $contract->applicant_name }} (كود: {{ $contract->contract_code }})</option>
                @endforeach
            </select>
        </div>

        <!-- Transaction Date -->
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">تاريخ الصرف <span class="text-danger">*</span></label>
            <input type="date" name="transaction_date" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>

        <!-- Notes -->
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">ملاحظات إضافية</label>
            <input type="text" name="notes" class="form-control" placeholder="ملاحظات اختيارية...">
        </div>

        <!-- Receipt Image (Optional) -->
        <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold"><i class="fa fa-camera text-primary ml-1"></i> صورة إيصال / وصل الصرف <small class="text-muted">(اختياري)</small></label>
            <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp">
            <small class="text-muted d-block mt-1">يمكنك إرفاق صورة فاتورة الشراء أو إيصال السداد أو الوصل الورقي (أقصى حجم 5 ميجابايت).</small>
        </div>
    </div>
</form>

<script>
    (function() {
        function checkExpenseType() {
            var isOther = $('#expenseTypeSelect').find(':selected').data('is-other');
            if (isOther == '1') {
                $('#otherDetailsWrap').removeClass('d-none');
                $('#otherDetailsInput').prop('required', true);
            } else {
                $('#otherDetailsWrap').addClass('d-none');
                $('#otherDetailsInput').prop('required', false);
            }
        }

        $('#expenseTypeSelect').on('change', checkExpenseType);

        $('#expenseTreasurySelect').on('change', function() {
            var curr = $(this).find(':selected').data('currency');
            if (curr) {
                $('#expenseCurrencySelect').val(curr).trigger('change');
            }
        });

        $('#expenseCurrencySelect').on('change', function() {
            var curr = $(this).val();
            $('#expenseCurrencySymbol').text(curr === 'EUR' ? '€' : 'ج.م');
        });

        $('input[name="expense_beneficiary"]').on('change', function() {
            var val = $(this).val();
            $('#adminSelectWrap').addClass('d-none');
            $('#supplierSelectWrap').addClass('d-none');
            $('#contractSelectWrap').addClass('d-none');
            $('#adminSelect').val('').trigger('change');
            $('#supplierSelect').val('').trigger('change');
            $('#contractSelect').val('').trigger('change');

            if (val === 'admin') {
                $('#adminSelectWrap').removeClass('d-none');
            } else if (val === 'supplier') {
                $('#supplierSelectWrap').removeClass('d-none');
            } else if (val === 'contract') {
                $('#contractSelectWrap').removeClass('d-none');
            }
        });

        checkExpenseType();
    })();
</script>
