<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('travelers.update', $traveler->id) }}">
    @csrf
    @method('PUT')
    <div class="row mt-0">
        <x-form.input class="col-md-6 col-sm-12" title="اسم المسافر" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="اسم المسافر" name="name" value="{{ $traveler->name }}" required/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="المحافظة بمصر" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="المحافظة" name="governorate" value="{{ $traveler->governorate }}"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="المسمى الوظيفي بإيطاليا" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="الوظيفة" name="job_title" value="{{ $traveler->job_title }}" required/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="المعلم أو المدينة الإيطالية (Location Chip)" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="المعلم" name="location" value="{{ $traveler->location }}"/>
        </x-form.input>

        <x-form.input class="col-md-4 col-sm-12" title="شارة العقد بالكارد (Verified Seal)" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="شارة العقد" name="badge" value="{{ $traveler->badge }}"/>
        </x-form.input>

        <x-form.input class="col-md-4 col-sm-6" title="كود العقد الموثق" :required="false">
            <input type="text" class="form-control form-control-solid font-monospace dir-ltr" placeholder="كود العقد" name="contract_code" value="{{ $traveler->contract_code }}"/>
        </x-form.input>

        <x-form.input class="col-md-4 col-sm-6" title="التقييم بالنجوم" :required="false">
            <select name="rating" class="form-control form-control-solid">
                <option value="5" {{ $traveler->rating == 5 ? 'selected' : '' }}>5 نجوم (ممتاز)</option>
                <option value="4" {{ $traveler->rating == 4 ? 'selected' : '' }}>4 نجوم (جيد جداً)</option>
                <option value="3" {{ $traveler->rating == 3 ? 'selected' : '' }}>3 نجوم (جيد)</option>
            </select>
        </x-form.input>

        <x-form.input class="col-12" title="قصة وتجربة السفر مع هلا النيل (Quote)" :required="true">
            <textarea class="form-control form-control-solid" rows="3" name="story" required>{{ $traveler->story }}</textarea>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="صورة المعلم الإيطالي الكبيرة (Landmark Image)" :required="false">
            <input type="file" class="form-control form-control-solid" name="landmark_image" accept="image/*"/>
            @if($traveler->landmark_image)
                <div class="mt-2">
                    <img src="{{ get_file($traveler->landmark_image) }}" class="rounded border shadow-sm" style="max-height: 70px;">
                </div>
            @endif
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="الصورة الشخصية للمسافر (Avatar)" :required="false">
            <input type="file" class="form-control form-control-solid" name="avatar" accept="image/*"/>
            @if($traveler->avatar)
                <div class="mt-2">
                    <img src="{{ get_file($traveler->avatar) }}" class="rounded-circle border shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                </div>
            @endif
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-6" title="الترتيب" :required="false">
            <input type="number" class="form-control form-control-solid numbersOnly" placeholder="0" name="sort_order" value="{{ $traveler->sort_order }}"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-6" title="الحالة" :required="true">
            <select name="is_active" class="form-control form-control-solid">
                <option value="1" {{ $traveler->is_active ? 'selected' : '' }}>مفعل</option>
                <option value="0" {{ !$traveler->is_active ? 'selected' : '' }}>غير مفعل</option>
            </select>
        </x-form.input>
    </div>
</form>
