<form action="{{ route('scheduled-items.update', $scheduledItem->id) }}" method="POST" id="form">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold">البيان / العنوان <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" value="{{ $scheduledItem->title }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">النوع <span class="text-danger">*</span></label>
            <select name="type" id="editSchedTypeSelect" class="form-control" required>
                <option value="expense" {{ $scheduledItem->type === 'expense' ? 'selected' : '' }}>مصروف مجدول (دفع)</option>
                <option value="revenue" {{ $scheduledItem->type === 'revenue' ? 'selected' : '' }}>إيراد مجدول (تحصيل)</option>
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">دورية التكرار <span class="text-danger">*</span></label>
            <select name="frequency" class="form-control" required>
                <option value="monthly" {{ $scheduledItem->frequency === 'monthly' ? 'selected' : '' }}>شهري (يتكرر كل شهر)</option>
                <option value="yearly" {{ $scheduledItem->frequency === 'yearly' ? 'selected' : '' }}>سنوي (يتكرر كل سنة)</option>
                <option value="once" {{ $scheduledItem->frequency === 'once' ? 'selected' : '' }}>تاريخ محدد (مرة واحدة)</option>
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label font-weight-bold">المبلغ <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0.01" name="amount" class="form-control font-weight-bold" value="{{ $scheduledItem->amount }}" required>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label font-weight-bold">العملة <span class="text-danger">*</span></label>
            <select name="currency" class="form-control" required>
                <option value="EGP" {{ $scheduledItem->currency === 'EGP' ? 'selected' : '' }}>جنيه مصري (EGP)</option>
                <option value="EUR" {{ $scheduledItem->currency === 'EUR' ? 'selected' : '' }}>يورو (€ EUR)</option>
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label font-weight-bold">تاريخ الدفع/التحصيل القادم <span class="text-danger">*</span></label>
            <input type="date" name="next_due_date" class="form-control" value="{{ $scheduledItem->next_due_date?->format('Y-m-d') }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">الخزينة الافتراضية <small class="text-muted">(اختياري)</small></label>
            <select name="treasury_id" class="form-control select2-basic">
                <option value="">-- اختر الخزينة --</option>
                @foreach($treasuries as $treasury)
                    <option value="{{ $treasury->id }}" {{ (int) $scheduledItem->treasury_id === (int) $treasury->id ? 'selected' : '' }}>
                        {{ $treasury->name }} ({{ $treasury->currency }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3 {{ $scheduledItem->type === 'revenue' ? 'd-none' : '' }}" id="editSchedExpenseTypeWrap">
            <label class="form-label font-weight-bold">بند المصروف <small class="text-muted">(اختياري)</small></label>
            <select name="expense_type_id" class="form-control select2-basic">
                <option value="">-- اختر بند المصروف --</option>
                @foreach($expenseTypes as $type)
                    <option value="{{ $type->id }}" {{ (int) $scheduledItem->expense_type_id === (int) $type->id ? 'selected' : '' }}>
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
                    <option value="{{ $supplier->id }}" {{ (int) $scheduledItem->supplier_id === (int) $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">الحالة <span class="text-danger">*</span></label>
            <select name="is_active" class="form-control" required>
                <option value="1" {{ $scheduledItem->is_active ? 'selected' : '' }}>مفعل</option>
                <option value="0" {{ ! $scheduledItem->is_active ? 'selected' : '' }}>متوقف</option>
            </select>
        </div>

        <div class="col-md-12 mb-2">
            <label class="form-label font-weight-bold">ملاحظات</label>
            <input type="text" name="notes" class="form-control" value="{{ $scheduledItem->notes }}">
        </div>
    </div>
</form>

<script>
    (function() {
        $('#editSchedTypeSelect').on('change', function() {
            if ($(this).val() === 'expense') {
                $('#editSchedExpenseTypeWrap').removeClass('d-none');
            } else {
                $('#editSchedExpenseTypeWrap').addClass('d-none');
            }
        });
    })();
</script>
