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

        <x-form.input class="col-md-6 col-sm-12" title="شارة العقد بالكارد (Verified Seal)" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="شارة العقد" name="badge" value="{{ $traveler->badge }}"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="المدينة الإيطالية" :required="false">
            <select name="italy_city_id" id="edit_traveler_italy_city_select" class="form-control form-control-solid">
                <option value="">اختر المدينة بإيطاليا...</option>
                @foreach($italyCities as $city)
                    <option value="{{ $city->id }}" {{ $traveler->italy_city_id === $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                @endforeach
            </select>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="المنطقة أو المعلم بإيطاليا" :required="false">
            <select name="italy_landmark_id" id="edit_traveler_italy_landmark_select" class="form-control form-control-solid">
                <option value="">اختر المعلم / المنطقة...</option>
                @foreach($italyLandmarks as $landmark)
                    <option value="{{ $landmark->id }}" data-city="{{ $landmark->italy_city_id }}" data-name="{{ $landmark->name }}" {{ $traveler->italy_landmark_id === $landmark->id ? 'selected' : '' }}>{{ $landmark->name }} ({{ $landmark->city?->name }})</option>
                @endforeach
            </select>
        </x-form.input>

        <x-form.input class="col-md-4 col-sm-12" title="نص الموقع المخصص (Location Chip)" :required="false">
            <input type="text" class="form-control form-control-solid" id="edit_traveler_location" placeholder="المعلم" name="location" value="{{ $traveler->location }}"/>
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

        <x-form.input class="col-md-6 col-sm-12" title="صورة المعلم الإيطالي (اختياري - افتراضياً تُؤخذ من المعلم)" :required="false">
            <input type="file" class="form-control form-control-solid" name="landmark_image" accept="image/*"/>
            @php
                $currentLandmarkImg = $traveler->effective_landmark_image;
                $currentLandmarkUrl = (file_exists(public_path($currentLandmarkImg)) || str_starts_with($currentLandmarkImg, 'Site/')) ? asset($currentLandmarkImg) : asset('storage/'.$currentLandmarkImg);
            @endphp
            <div class="mt-2 d-flex align-items-center gap-2">
                <img src="{{ $currentLandmarkUrl }}" class="rounded border shadow-sm" style="max-height: 55px;" alt="معلم إيطالي">
                <small class="text-muted">الصورة الحالية للمعلم</small>
            </div>
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

<script>
    (function () {
        var citySelect = document.getElementById('edit_traveler_italy_city_select');
        var landmarkSelect = document.getElementById('edit_traveler_italy_landmark_select');
        var locationInput = document.getElementById('edit_traveler_location');

        if (citySelect && landmarkSelect) {
            citySelect.addEventListener('change', function () {
                var selectedCityId = this.value;
                var selectedCityText = this.options[this.selectedIndex] ? this.options[this.selectedIndex].text : '';

                Array.from(landmarkSelect.options).forEach(function (option) {
                    if (!option.value) return;
                    var landmarkCity = option.getAttribute('data-city');
                    if (!selectedCityId || landmarkCity === selectedCityId) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                });

                if (locationInput && selectedCityText && selectedCityId && !locationInput.value) {
                    locationInput.value = selectedCityText.split('(')[0].trim();
                }
            });

            landmarkSelect.addEventListener('change', function () {
                var selectedOption = this.options[this.selectedIndex];
                if (selectedOption && selectedOption.value) {
                    var landmarkName = selectedOption.getAttribute('data-name') || selectedOption.text.split('(')[0].trim();
                    var cityName = citySelect.options[citySelect.selectedIndex] ? citySelect.options[citySelect.selectedIndex].text.split('(')[0].trim() : '';
                    if (locationInput) {
                        locationInput.value = (cityName ? cityName + ' - ' : '') + landmarkName;
                    }
                }
            });
        }
    })();
</script>
