<form action="{{ route('expenses.update', $expense->id) }}" method="POST" id="form" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <!-- Main Expense Category -->
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">التصنيف الرئيسي للمصروف</label>
            @php $currentCatId = $expense->expenseType?->expense_category_id; @endphp
            <select id="editExpenseCategorySelect" class="form-control select2-basic">
                <option value="">-- جميع التصنيفات الرئيسية --</option>
                @foreach($expenseCategories as $category)
                    <option value="{{ $category->id }}" {{ (int) $currentCatId === (int) $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
                <option value="none" {{ $expense->expenseType && $currentCatId === null ? 'selected' : '' }}>بنود عامة (بدون تصنيف)</option>
            </select>
        </div>

        <!-- Expense Type (Sub-category) -->
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">نوع / بند المصروف الفرعي <span class="text-danger">*</span></label>
            <select name="expense_type_id" id="editExpenseTypeSelect" class="form-control select2-basic" required>
                @foreach($expenseTypes as $type)
                    <option value="{{ $type->id }}"
                            data-category-id="{{ $type->expense_category_id ?? 'none' }}"
                            data-is-other="{{ $type->is_other ? '1' : '0' }}"
                            {{ $expense->expense_type_id == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Treasury -->
        <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold">الخزينة المنصرف منها <span class="text-danger">*</span></label>
            <select name="treasury_id" class="form-control select2-basic" id="editExpenseTreasurySelect" required>
                @foreach($treasuries as $treasury)
                    <option value="{{ $treasury->id }}" data-currency="{{ $treasury->currency }}" {{ $expense->treasury_id == $treasury->id ? 'selected' : '' }}>
                        {{ $treasury->name }} ({{ $treasury->currency }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Currency -->
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">عملة الصرف <span class="text-danger">*</span></label>
            <select name="currency" id="editExpenseCurrencySelect" class="form-control" required>
                <option value="EGP" {{ $expense->currency === 'EGP' ? 'selected' : '' }}>جنيه مصري (EGP)</option>
                <option value="EUR" {{ $expense->currency === 'EUR' ? 'selected' : '' }}>يورو (€ EUR)</option>
            </select>
        </div>

        <!-- Amount -->
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">المبلغ المنصرف <span class="text-danger">*</span></label>
            <div class="input-group">
                <input type="number" step="0.01" min="0.01" name="amount" class="form-control font-weight-bold text-danger fs-16" value="{{ $expense->amount }}" required>
                <div class="input-group-append">
                    <span class="input-group-text font-weight-bold" id="editExpenseCurrencySymbol">{{ $expense->currency === 'EUR' ? '€' : 'ج.م' }}</span>
                </div>
            </div>
        </div>

        <!-- Other Details -->
        <div class="col-md-12 mb-3 {{ $expense->expenseType?->is_other ? '' : 'd-none' }}" id="editOtherDetailsWrap">
            <div class="p-3 bg-light rounded border border-warning">
                <label class="form-label font-weight-bold text-dark">
                    <i class="fe fe-alert-circle text-warning ml-1"></i> بيان وتفاصيل المصروف
                </label>
                <input type="text" name="other_details" id="editOtherDetailsInput" class="form-control font-weight-semibold" value="{{ $expense->other_details }}">
            </div>
        </div>

        <!-- Beneficiary Admin -->
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">الموظف (اختياري - رواتب/سلف)</label>
            <select name="admin_id" class="form-control select2-basic">
                <option value="">-- بدون ربط بموظف --</option>
                @foreach($admins as $adminUser)
                    <option value="{{ $adminUser->id }}" {{ $expense->admin_id == $adminUser->id ? 'selected' : '' }}>{{ $adminUser->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Beneficiary Supplier -->
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">المورد (اختياري)</label>
            <select name="supplier_id" class="form-control select2-basic">
                <option value="">-- بدون ربط بمورد --</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ $expense->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Beneficiary Contract -->
        <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold">العقد المرتبط (اختياري)</label>
            <select name="contract_tracker_id" class="form-control select2-basic">
                <option value="">-- بدون ربط بعقد --</option>
                @foreach($contracts as $contract)
                    <option value="{{ $contract->id }}" {{ $expense->contract_tracker_id == $contract->id ? 'selected' : '' }}>
                        {{ $contract->applicant_name }} (كود: {{ $contract->contract_code }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Transaction Date -->
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">تاريخ الصرف <span class="text-danger">*</span></label>
            <input type="date" name="transaction_date" class="form-control" value="{{ $expense->transaction_date?->format('Y-m-d') }}" required>
        </div>

        <!-- Notes -->
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">ملاحظات إضافية</label>
            <input type="text" name="notes" class="form-control" value="{{ $expense->notes }}">
        </div>

        <!-- Receipt Image (Optional) -->
        <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold"><i class="fa fa-camera text-primary ml-1"></i> صورة إيصال / وصل الصرف <small class="text-muted">(اختياري - اترك فارغاً للإبقاء على الحالي)</small></label>
            <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp">
            @if($expense->image)
                <div class="mt-2 d-flex align-items-center" style="gap: 12px;">
                    <img src="{{ asset('storage/'.$expense->image) }}" class="rounded border shadow-sm" style="max-height: 60px; max-width: 90px; object-fit: cover;" alt="وصل صرف #{{ $expense->id }}">
                    <a href="{{ asset('storage/'.$expense->image) }}" target="_blank" class="btn btn-sm btn-outline-info font-weight-bold">
                        <i class="fa fa-external-link ml-1"></i> عرض الإيصال الحالي
                    </a>
                </div>
            @endif
        </div>
    </div>
</form>

<script>
    (function() {
        var allEditExpenseTypeOptions = $('#editExpenseTypeSelect option').clone();

        function filterEditExpenseTypes(keepCurrentValue) {
            var categoryId = $('#editExpenseCategorySelect').val();
            var $typeSelect = $('#editExpenseTypeSelect');
            var currentVal = keepCurrentValue ? $typeSelect.val() : '';

            $typeSelect.empty();
            $typeSelect.append('<option value="">-- اختر البند الفرعي --</option>');

            allEditExpenseTypeOptions.each(function() {
                var val = $(this).val();
                if (!val) return;
                var optCatId = String($(this).data('category-id'));
                if (!categoryId || optCatId === String(categoryId)) {
                    $typeSelect.append($(this).clone());
                }
            });

            if (currentVal && $typeSelect.find('option[value="' + currentVal + '"]').length) {
                $typeSelect.val(currentVal);
            } else {
                $typeSelect.val('');
            }
            $typeSelect.trigger('change');
        }

        $('#editExpenseCategorySelect').on('change', function() {
            filterEditExpenseTypes(false);
        });

        $('#editExpenseTypeSelect').on('change', function() {
            var isOther = $(this).find(':selected').data('is-other');
            if (isOther == '1') {
                $('#editOtherDetailsWrap').removeClass('d-none');
                $('#editOtherDetailsInput').prop('required', true);
            } else {
                $('#editOtherDetailsWrap').addClass('d-none');
                $('#editOtherDetailsInput').prop('required', false);
            }
        });

        $('#editExpenseCurrencySelect').on('change', function() {
            var curr = $(this).val();
            $('#editExpenseCurrencySymbol').text(curr === 'EUR' ? '€' : 'ج.م');
        });

        filterEditExpenseTypes(true);
    })();
</script>
