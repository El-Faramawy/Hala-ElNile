<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('job-categories.store') }}">
    @csrf
    <div class="row mt-0">
        <x-form.input class="col-sm-6" title="اسم القسم / المجال" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="مثال: مطاعم وفندقة" name="name" required/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="الترتيب" :required="false">
            <input type="number" class="form-control form-control-solid numbersOnly" placeholder="الترتيب" name="sort_order" value="0"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="الحالة" :required="true">
            <select name="is_active" class="form-control form-control-solid">
                <option value="1">مفعل</option>
                <option value="0">غير مفعل</option>
            </select>
        </x-form.input>

        <x-form.input class="col-sm-6" title="أين يظهر القسم؟" :required="true">
            <select name="display_location" class="form-control form-control-solid" required>
                <option value="both" selected>الموقع ولوحة التحكم معاً (الكل)</option>
                <option value="website">في الموقع الإلكتروني فقط</option>
                <option value="dashboard">في لوحة التحكم فقط</option>
            </select>
        </x-form.input>

        <x-form.input class="col-sm-6" title="أيقونة القسم (من أيقونات الموقع)" :required="false">
            <select name="icon" class="form-control form-control-solid">
                <option value="fa-solid fa-utensils">مطاعم وفندقة (fa-solid fa-utensils)</option>
                <option value="fa-solid fa-seedling">عمالة زراعية (fa-solid fa-seedling)</option>
                <option value="fa-solid fa-trowel-bricks">تشييد وبناء (fa-solid fa-trowel-bricks)</option>
                <option value="fa-solid fa-truck-fast">نقل ولوجستيات (fa-solid fa-truck-fast)</option>
                <option value="fa-solid fa-wrench">صيانة ومهن حرفية (fa-solid fa-wrench)</option>
                <option value="fa-solid fa-hotel">ضيافة ومنتجعات (fa-solid fa-hotel)</option>
                <option value="fa-solid fa-briefcase" selected>وظائف عامة (fa-solid fa-briefcase)</option>
            </select>
        </x-form.input>

        <x-form.input class="col-sm-12" title="أو رفع ملف أيقونة / صورة مخصصة (اختياري)" :required="false">
            <input type="file" class="form-control form-control-solid" name="icon_file" accept="image/*,image/svg+xml"/>
            <small class="text-muted d-block mt-1">في حال رفع صورة، سيتم اعتمادها بدلاً من الأيقونة المحددة أعلاه.</small>
        </x-form.input>
    </div>
</form>
