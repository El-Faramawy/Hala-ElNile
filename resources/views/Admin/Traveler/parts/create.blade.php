<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('travelers.store') }}">
    @csrf
    <div class="row mt-0">
        <!-- Applicant Search & Auto-fill Box -->
        <div class="col-12 mb-3">
            <div class="p-3 bg-light rounded border position-relative">
                <label class="fw-bold small text-dark mb-1">
                    <i class="fa fa-search text-primary mr-1"></i> استيراد وتعبئة تلقائية من العقود أو طلبات التوظيف (اختياري):
                </label>
                <div class="input-group">
                    <input type="text" id="applicant_search_input" class="form-control form-control-solid" placeholder="ابحث بالاسم أو كود العقد أو رقم الهاتف لجلب البيانات تلقائياً...">
                    <div class="input-group-append">
                        <span class="input-group-text bg-white"><i class="fa fa-user-plus text-primary"></i></span>
                    </div>
                </div>
                <div id="applicant_search_results" class="list-group position-absolute shadow-lg" style="display: none; z-index: 1050; left: 15px; right: 15px; max-height: 220px; overflow-y: auto;"></div>
                <small class="text-muted d-block mt-1">عند اختيار أحد المتقدمين، سيتم ملء حقول الاسم والمحافظة والوظيفة وكود العقد تلقائياً مع إمكانية تعديلها بحرية.</small>
            </div>
        </div>

        <x-form.input class="col-md-6 col-sm-12" title="اسم المسافر" :required="true">
            <input type="text" class="form-control form-control-solid" id="traveler_name" placeholder="مثال: أحمد صابر عبد السلام" name="name" required/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="المحافظة بمصر" :required="false">
            <input type="text" class="form-control form-control-solid" id="traveler_governorate" placeholder="مثال: محافظة الغربية" name="governorate"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="المسمى الوظيفي بإيطاليا" :required="true">
            <input type="text" class="form-control form-control-solid" id="traveler_job_title" placeholder="مثال: فني تشييد وبناء بروما" name="job_title" required/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="شارة العقد بالكارد (Verified Seal)" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="مثال: عقد دائم موثق / شيف مطعم" name="badge"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="المدينة الإيطالية" :required="false">
            <select name="italy_city_id" id="traveler_italy_city_select" class="form-control form-control-solid">
                <option value="">اختر المدينة بإيطاليا...</option>
                @foreach($italyCities as $city)
                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                @endforeach
            </select>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="المنطقة أو المعلم بإيطاليا" :required="false">
            <select name="italy_landmark_id" id="traveler_italy_landmark_select" class="form-control form-control-solid">
                <option value="">اختر المعلم / المنطقة...</option>
                @foreach($italyLandmarks as $landmark)
                    <option value="{{ $landmark->id }}" data-city="{{ $landmark->italy_city_id }}" data-name="{{ $landmark->name }}">{{ $landmark->name }} ({{ $landmark->city?->name }})</option>
                @endforeach
            </select>
        </x-form.input>

        <x-form.input class="col-md-4 col-sm-12" title="نص الموقع المخصص (Location Chip)" :required="false">
            <input type="text" class="form-control form-control-solid" id="traveler_location" placeholder="مثال: روما - الكولوسيوم" name="location"/>
        </x-form.input>

        <x-form.input class="col-md-4 col-sm-6" title="كود العقد الموثق" :required="false">
            <input type="text" class="form-control form-control-solid font-monospace dir-ltr" id="traveler_contract_code" placeholder="مثال: HS-5120" name="contract_code"/>
        </x-form.input>

        <x-form.input class="col-md-4 col-sm-6" title="التقييم بالنجوم (1 إلى 5)" :required="false">
            <select name="rating" class="form-control form-control-solid">
                <option value="5" selected>5 نجوم (ممتاز)</option>
                <option value="4">4 نجوم (جيد جداً)</option>
                <option value="3">3 نجوم (جيد)</option>
            </select>
        </x-form.input>

        <x-form.input class="col-12" title="قصة وتجربة السفر مع هلا النيل (Quote)" :required="true">
            <textarea class="form-control form-control-solid" rows="3" placeholder="الحمد لله سافرت عن طريق شركة هلا النيل... السكن محترم والراتب بالبنك..." name="story" required></textarea>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="صورة المعلم الإيطالي (اختياري - افتراضياً تُؤخذ من المعلم المختار)" :required="false">
            <input type="file" class="form-control form-control-solid" name="landmark_image" accept="image/*"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="الصورة الشخصية للمسافر (Avatar)" :required="false">
            <input type="file" class="form-control form-control-solid" name="avatar" accept="image/*"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-6" title="الترتيب" :required="false">
            <input type="number" class="form-control form-control-solid numbersOnly" placeholder="0" name="sort_order" value="0"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-6" title="الحالة" :required="true">
            <select name="is_active" class="form-control form-control-solid">
                <option value="1">مفعل</option>
                <option value="0">غير مفعل</option>
            </select>
        </x-form.input>
    </div>
</form>

<script>
    (function () {
        // 1. Applicant Search & Auto-fill
        var searchInput = document.getElementById('applicant_search_input');
        var resultsBox = document.getElementById('applicant_search_results');
        var searchTimeout = null;

        if (searchInput && resultsBox) {
            searchInput.addEventListener('input', function () {
                var query = this.value.trim();
                clearTimeout(searchTimeout);

                if (query.length < 2) {
                    resultsBox.style.display = 'none';
                    resultsBox.innerHTML = '';
                    return;
                }

                searchTimeout = setTimeout(function () {
                    fetch("{{ route('travelers.searchApplicants') }}?q=" + encodeURIComponent(query), {
                        headers: { 'Accept': 'application/json' }
                    })
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        resultsBox.innerHTML = '';
                        if (!Array.isArray(data) || data.length === 0) {
                            resultsBox.innerHTML = '<div class="list-group-item text-muted small p-2 text-center">لا توجد نتائج مطابقة</div>';
                            resultsBox.style.display = 'block';
                            return;
                        }

                        data.forEach(function (item) {
                            var btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = 'list-group-item list-group-item-action p-2 small text-right d-flex justify-content-between align-items-center';
                            btn.innerHTML = '<span><strong>' + (item.name || '') + '</strong> <span class="badge badge-light border text-primary font-monospace">' + (item.contract_code ? '#' + item.contract_code : '') + '</span> <span class="text-muted">(' + (item.job_title || '') + ')</span></span><span class="badge badge-success px-2 py-1">' + (item.source || '') + '</span>';

                            btn.addEventListener('click', function () {
                                if (document.getElementById('traveler_name')) document.getElementById('traveler_name').value = item.name || '';
                                if (document.getElementById('traveler_governorate')) document.getElementById('traveler_governorate').value = item.governorate || '';
                                if (document.getElementById('traveler_job_title')) document.getElementById('traveler_job_title').value = item.job_title || '';
                                if (document.getElementById('traveler_contract_code')) document.getElementById('traveler_contract_code').value = item.contract_code || '';
                                resultsBox.style.display = 'none';
                                searchInput.value = item.name + (item.contract_code ? ' (#' + item.contract_code + ')' : '');
                            });

                            resultsBox.appendChild(btn);
                        });

                        resultsBox.style.display = 'block';
                    })
                    .catch(function () {
                        resultsBox.style.display = 'none';
                    });
                }, 300);
            });

            document.addEventListener('click', function (e) {
                if (!searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
                    resultsBox.style.display = 'none';
                }
            });
        }

        // 2. City & Landmark Dynamic Filter
        var citySelect = document.getElementById('traveler_italy_city_select');
        var landmarkSelect = document.getElementById('traveler_italy_landmark_select');
        var locationInput = document.getElementById('traveler_location');

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

                if (locationInput && selectedCityText && selectedCityId) {
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
