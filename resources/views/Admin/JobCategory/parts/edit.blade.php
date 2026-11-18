<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('job-categories.update', $jobCategory->id) }}">
    @csrf
    @method('PUT')
    <div class="row mt-0">
        <x-form.input class="col-sm-6" title="اسم القسم / المجال" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="الاسم" name="name" value="{{ $jobCategory->name }}" required/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="الترتيب" :required="false">
            <input type="number" class="form-control form-control-solid numbersOnly" placeholder="الترتيب" name="sort_order" value="{{ $jobCategory->sort_order }}"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="الحالة" :required="true">
            <select name="is_active" class="form-control form-control-solid">
                <option value="1" {{ $jobCategory->is_active ? 'selected' : '' }}>مفعل</option>
                <option value="0" {{ ! $jobCategory->is_active ? 'selected' : '' }}>غير مفعل</option>
            </select>
        </x-form.input>

        <x-form.input class="col-sm-6" title="أيقونة القسم (من أيقونات الموقع)" :required="false">
            <select name="icon" class="form-control form-control-solid">
                <option value="fa-solid fa-utensils" {{ $jobCategory->icon === 'fa-solid fa-utensils' ? 'selected' : '' }}>مطاعم وفندقة (fa-solid fa-utensils)</option>
                <option value="fa-solid fa-seedling" {{ $jobCategory->icon === 'fa-solid fa-seedling' ? 'selected' : '' }}>عمالة زراعية (fa-solid fa-seedling)</option>
                <option value="fa-solid fa-trowel-bricks" {{ $jobCategory->icon === 'fa-solid fa-trowel-bricks' ? 'selected' : '' }}>تشييد وبناء (fa-solid fa-trowel-bricks)</option>
                <option value="fa-solid fa-truck-fast" {{ $jobCategory->icon === 'fa-solid fa-truck-fast' ? 'selected' : '' }}>نقل ولوجستيات (fa-solid fa-truck-fast)</option>
                <option value="fa-solid fa-wrench" {{ $jobCategory->icon === 'fa-solid fa-wrench' ? 'selected' : '' }}>صيانة ومهن حرفية (fa-solid fa-wrench)</option>
                <option value="fa-solid fa-hotel" {{ $jobCategory->icon === 'fa-solid fa-hotel' ? 'selected' : '' }}>ضيافة ومنتجعات (fa-solid fa-hotel)</option>
                <option value="fa-solid fa-briefcase" {{ $jobCategory->icon === 'fa-solid fa-briefcase' || empty($jobCategory->icon) ? 'selected' : '' }}>وظائف عامة (fa-solid fa-briefcase)</option>
            </select>
        </x-form.input>

        <x-form.input class="col-sm-12" title="أو رفع ملف أيقونة / صورة مخصصة (اختياري)" :required="false">
            <input type="file" class="form-control form-control-solid" name="icon_file" accept="image/*,image/svg+xml"/>
            @if($jobCategory->icon)
                <div class="mt-2 d-flex align-items-center gap-2">
                    <span class="text-muted fs-12 ml-2">المعاينة الحالية:</span>
                    {!! tableIcon($jobCategory->icon, 'fa-solid fa-briefcase') !!}
                </div>
            @endif
        </x-form.input>
    </div>
</form>
