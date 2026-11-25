<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('italy-cities.store') }}">
    @csrf
    <div class="row mt-0">
        <x-form.input class="col-md-8 col-sm-12" title="اسم المدينة الإيطالية" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="مثال: ميلانو (Milano) أو بريشيا" name="name" required/>
        </x-form.input>

        <x-form.input class="col-md-4 col-sm-6" title="الترتيب" :required="false">
            <input type="number" class="form-control form-control-solid numbersOnly" placeholder="0" name="sort_order" value="0"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="صورة معبرة للمدينة (اختياري)" :required="false">
            <input type="file" class="form-control form-control-solid" name="image" accept="image/*"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-6" title="الحالة" :required="true">
            <select name="is_active" class="form-control form-control-solid">
                <option value="1">مفعل</option>
                <option value="0">غير مفعل</option>
            </select>
        </x-form.input>
    </div>
</form>
