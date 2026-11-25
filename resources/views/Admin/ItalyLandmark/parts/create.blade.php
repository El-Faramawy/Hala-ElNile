<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('italy-landmarks.store') }}">
    @csrf
    <div class="row mt-0">
        <x-form.input class="col-md-6 col-sm-12" title="المدينة بإيطاليا" :required="true">
            <select name="italy_city_id" class="form-control form-control-solid" required>
                <option value="">اختر المدينة...</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                @endforeach
            </select>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="اسم المنطقة أو المعلم" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="مثال: ساحة الدومو (Piazza del Duomo) أو الكولوسيوم" name="name" required/>
        </x-form.input>

        <x-form.input class="col-12" title="نبذة أو وصف المعلم (اختياري)" :required="false">
            <textarea class="form-control form-control-solid" rows="2" placeholder="اكتب نبذة مختصرة عن هذا المعلم بإيطاليا..." name="description"></textarea>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="صورة المعلم (تظهر في الموقع وبطاقات المسافرين)" :required="false">
            <input type="file" class="form-control form-control-solid" name="image" accept="image/*"/>
        </x-form.input>

        <x-form.input class="col-md-3 col-sm-6" title="الترتيب" :required="false">
            <input type="number" class="form-control form-control-solid numbersOnly" placeholder="0" name="sort_order" value="0"/>
        </x-form.input>

        <x-form.input class="col-md-3 col-sm-6" title="الحالة" :required="true">
            <select name="is_active" class="form-control form-control-solid">
                <option value="1">مفعل</option>
                <option value="0">غير مفعل</option>
            </select>
        </x-form.input>
    </div>
</form>
