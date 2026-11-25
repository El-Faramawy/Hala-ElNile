<form action="{{ route('suppliers.store') }}" method="POST" id="form">
    @csrf
    <div class="row">
        <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold">اسم المورد / الوكيل <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="مثال: مكتب النصر للطيران / وكيل روما" required>
        </div>

        <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold">رقم الهاتف للتواصل</label>
            <input type="text" name="phone" class="form-control" placeholder="010xxxxxxxx" dir="ltr">
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
