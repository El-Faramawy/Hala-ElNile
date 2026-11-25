<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('italy-cities.update', $italyCity->id) }}">
    @csrf
    @method('PUT')
    <div class="row mt-0">
        <x-form.input class="col-md-8 col-sm-12" title="اسم المدينة الإيطالية" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="اسم المدينة" name="name" value="{{ $italyCity->name }}" required/>
        </x-form.input>

        <x-form.input class="col-md-4 col-sm-6" title="الترتيب" :required="false">
            <input type="number" class="form-control form-control-solid numbersOnly" placeholder="0" name="sort_order" value="{{ $italyCity->sort_order }}"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="صورة معبرة للمدينة" :required="false">
            <input type="file" class="form-control form-control-solid" name="image" accept="image/*"/>
            @if($italyCity->image)
                @php
                    $cityImgUrl = file_exists(public_path($italyCity->image)) ? asset($italyCity->image) : asset('storage/'.$italyCity->image);
                @endphp
                <div class="mt-2">
                    <img src="{{ $cityImgUrl }}" class="rounded border shadow-sm" style="max-height: 60px;" alt="{{ $italyCity->name }}">
                </div>
            @endif
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-6" title="الحالة" :required="true">
            <select name="is_active" class="form-control form-control-solid">
                <option value="1" {{ $italyCity->is_active ? 'selected' : '' }}>مفعل</option>
                <option value="0" {{ !$italyCity->is_active ? 'selected' : '' }}>غير مفعل</option>
            </select>
        </x-form.input>
    </div>
</form>
