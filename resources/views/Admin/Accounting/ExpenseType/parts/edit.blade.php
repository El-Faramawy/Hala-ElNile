<form action="{{ route('expense-types.update', $expenseType->id) }}" method="POST" id="form">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold">التصنيف الرئيسي (Category)</label>
            <select name="expense_category_id" class="form-control select2-basic">
                <option value="" {{ $expenseType->expense_category_id === null ? 'selected' : '' }}>-- بدون تصنيف رئيسي (بند عام) --</option>
                @foreach($expenseCategories as $category)
                    <option value="{{ $category->id }}" {{ (int) $expenseType->expense_category_id === (int) $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold">اسم البند الفرعي <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ $expenseType->name }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">هل يتطلب تفاصيل إضافية إجبارية عند الصرف؟</label>
            <select name="is_other" class="form-control" required>
                <option value="0" {{ ! $expenseType->is_other ? 'selected' : '' }}>لا - بند قياسي</option>
                <option value="1" {{ $expenseType->is_other ? 'selected' : '' }}>نعم - يتطلب كتابة بيان مفصل</option>
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">الحالة <span class="text-danger">*</span></label>
            <select name="is_active" class="form-control" required>
                <option value="1" {{ $expenseType->is_active ? 'selected' : '' }}>مفعل</option>
                <option value="0" {{ ! $expenseType->is_active ? 'selected' : '' }}>غير مفعل</option>
            </select>
        </div>
    </div>
</form>
