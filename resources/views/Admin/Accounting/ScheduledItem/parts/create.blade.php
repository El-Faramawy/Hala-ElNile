<form action="{{ route('scheduled-items.store') }}" method="POST" id="form">
    @csrf
    <div class="row">
        <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold">البيان / العنوان <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" placeholder="مثال: إيجار المكتب / اشتراك سنوي / تحصيل..." required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">النوع <span class="text-danger">*</span></label>
            <select name="type" id="schedTypeSelect" class="form-control" required>
                <option value="expense" selected>مصروف مجدول (دفع)</option>
                <option value="revenue">إيراد مجدول (تحصيل)</option>
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">دورية التكرار <span class="text-danger">*</span></label>
            <select name="frequency" class="form-control" required>
                <option value="monthly" selected>شهري (يتكرر كل شهر)</option>
                <option value="yearly">سنوي (يتكرر كل سنة)</option>
                <option value="once">تاريخ محدد (مرة واحدة)</option>
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label font-weight-bold">المبلغ <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0.01" name="amount" class="form-control font-weight-bold" placeholder="0.00" required>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label font-weight-bold">العملة <span class="text-danger">*</span></label>
            <select name="currency" class="form-control" required>
                <option value="EGP" selected>جنيه مصري (EGP)</option>
                <option value="EUR">يورو (€ EUR)</option>
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label font-weight-bold">تاريخ الدفع/التحصيل القادم <span class="text-danger">*</span></label>
            <input type="date" name="next_due_date" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">الخزينة الافتراضية <small class="text-muted">(اختياري)</small></label>
            <select name="treasury_id" class="form-control select2-basic">
                <option value="">-- اختر الخزينة --</option>
                @foreach($treasuries as $treasury)
                    <option value="{{ $treasury->id }}">{{ $treasury->name }} ({{ $treasury->currency }})</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3" id="schedExpenseTypeWrap">
            <label class="form-label font-weight-bold">بند المصروف <small class="text-muted">(اختياري)</small></label>
            <select name="expense_type_id" class="form-control select2-basic">
                <option value="">-- اختر بند المصروف --</option>
                @foreach($expenseTypes as $type)
                    <option value="{{ $type->id }}">
                        {{ $type->expenseCategory ? $type->expenseCategory->name.' — ' : '' }}{{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">المورد <small class="text-muted">(اختياري)</small></label>
            <select name="supplier_id" class="form-control select2-basic">
                <option value="">-- بدون مورد --</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">الحالة <span class="text-danger">*</span></label>
            <select name="is_active" class="form-control" required>
                <option value="1" selected>مفعل</option>
                <option value="0">متوقف</option>
            </select>
        </div>

        <div class="col-md-12 mb-2">
            <label class="form-label font-weight-bold">ملاحظات</label>
            <input type="text" name="notes" class="form-control" placeholder="ملاحظات اختيارية...">
        </div>
    </div>
</form>

<script>
    (function() {
        $('#schedTypeSelect').on('change', function() {
            if ($(this).val() === 'expense') {
                $('#schedExpenseTypeWrap').removeClass('d-none');
            } else {
                $('#schedExpenseTypeWrap').addClass('d-none');
            }
        });
    })();
</script>
