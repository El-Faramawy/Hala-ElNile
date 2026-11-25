<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('italy-landmarks.update', $italyLandmark->id) }}">
    @csrf
    @method('PUT')
    <div class="row mt-0">
        <x-form.input class="col-md-6 col-sm-12" title="المدينة بإيطاليا" :required="true">
            <select name="italy_city_id" class="form-control form-control-solid" required>
                <option value="">اختر المدينة...</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}" {{ $italyLandmark->italy_city_id === $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                @endforeach
            </select>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="اسم المنطقة أو المعلم" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="اسم المنطقة أو المعلم" name="name" value="{{ $italyLandmark->name }}" required/>
        </x-form.input>

        <x-form.input class="col-12" title="نبذة أو وصف المعلم" :required="false">
            <textarea class="form-control form-control-solid" rows="2" name="description">{{ $italyLandmark->description }}</textarea>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="صورة المعلم" :required="false">
            <input type="file" class="form-control form-control-solid" name="image" accept="image/*"/>
            @if($italyLandmark->image)
                @php
                    $landmarkImgUrl = file_exists(public_path($italyLandmark->image)) ? asset($italyLandmark->image) : asset('storage/'.$italyLandmark->image);
                @endphp
                <div class="mt-2">
                    <img src="{{ $landmarkImgUrl }}" class="rounded border shadow-sm" style="max-height: 60px;" alt="{{ $italyLandmark->name }}">
                </div>
            @endif
        </x-form.input>

        <x-form.input class="col-md-3 col-sm-6" title="الترتيب" :required="false">
            <input type="number" class="form-control form-control-solid numbersOnly" placeholder="0" name="sort_order" value="{{ $italyLandmark->sort_order }}"/>
        </x-form.input>

        <x-form.input class="col-md-3 col-sm-6" title="الحالة" :required="true">
            <select name="is_active" class="form-control form-control-solid">
                <option value="1" {{ $italyLandmark->is_active ? 'selected' : '' }}>مفعل</option>
                <option value="0" {{ !$italyLandmark->is_active ? 'selected' : '' }}>غير مفعل</option>
            </select>
        </x-form.input>
    </div>
</form>
