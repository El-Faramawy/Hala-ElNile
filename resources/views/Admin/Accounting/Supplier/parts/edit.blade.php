<form action="{{ route('suppliers.update', $supplier->id) }}" method="POST" id="form">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold">اسم المورد / الوكيل <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ $supplier->name }}" required>
        </div>

        <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold">رقم الهاتف للتواصل</label>
            <input type="text" name="phone" class="form-control" value="{{ $supplier->phone }}" dir="ltr">
        </div>

        <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold">الحالة <span class="text-danger">*</span></label>
            <select name="is_active" class="form-control" required>
                <option value="1" {{ $supplier->is_active ? 'selected' : '' }}>مفعل</option>
                <option value="0" {{ ! $supplier->is_active ? 'selected' : '' }}>غير مفعل</option>
            </select>
        </div>
    </div>
</form>
