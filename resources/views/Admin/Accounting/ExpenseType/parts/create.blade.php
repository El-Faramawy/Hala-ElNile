<form action="{{ route('expense-types.store') }}" method="POST" id="form">
    @csrf
    <div class="row">
        <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold">اسم بند المصروف <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="مثال: رواتب موظفين / رسوم توثيق" required>
        </div>

        <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold">هل يتطلب تفاصيل إضافية إجبارية عند الصرف؟</label>
            <select name="is_other" class="form-control" required>
                <option value="0" selected>لا - بند قياسي</option>
                <option value="1">نعم - مثل بند (أخرى) يتطلب كتابة بيان مفصل</option>
            </select>
        </div>

        <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold">الحالة <span class="text-danger">*</span></label>
            <select name="is_active" class="form-control" required>
                <option value="1" selected>مفعل</option>
                <option value="0">غير مفعل</option>
            </select>
        </div>
    </div>
</form>
