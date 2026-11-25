<form action="{{ route('treasuries.update', $treasury->id) }}" method="POST" id="form">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold">اسم الخزينة / الحساب <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ $treasury->name }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">العملة <span class="text-danger">*</span></label>
            <select name="currency" class="form-control" required>
                <option value="EGP" {{ $treasury->currency === 'EGP' ? 'selected' : '' }}>جنيه مصري (EGP)</option>
                <option value="EUR" {{ $treasury->currency === 'EUR' ? 'selected' : '' }}>يورو (€ EUR)</option>
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">الرصيد الحالي</label>
            <input type="number" step="0.01" name="balance" class="form-control font-weight-bold" value="{{ $treasury->balance }}">
        </div>

        <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold">الحالة <span class="text-danger">*</span></label>
            <select name="is_active" class="form-control" required>
                <option value="1" {{ $treasury->is_active ? 'selected' : '' }}>مفعل</option>
                <option value="0" {{ ! $treasury->is_active ? 'selected' : '' }}>غير مفعل</option>
            </select>
        </div>
    </div>
</form>
