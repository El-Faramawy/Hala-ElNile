<form action="{{ route('treasuries.store') }}" method="POST" id="form">
    @csrf
    <div class="row">
        <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold">اسم الخزينة / الحساب <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="مثال: إنستاباي الرئيسي / خزينة كاش" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">العملة <span class="text-danger">*</span></label>
            <select name="currency" class="form-control" required>
                <option value="EGP" selected>جنيه مصري (EGP)</option>
                <option value="EUR">يورو (€ EUR)</option>
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">الرصيد الافتتاحي</label>
            <input type="number" step="0.01" name="balance" class="form-control font-weight-bold" value="0.00">
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
