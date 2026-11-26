<form action="{{ route('expense-categories.store') }}" method="POST" id="form">
    @csrf
    <div class="row">
        <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold">اسم التصنيف الرئيسي <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="مثال: تأسيس شركة / دكتور فاير" required>
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
