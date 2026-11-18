<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('jobs.update', $job->id) }}">
    @csrf
    @method('PUT')
    <div class="row mt-0">
        <x-form.input class="col-md-6 col-sm-12" title="المسمى الوظيفي" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="المسمى الوظيفي" name="title" value="{{ $job->title }}" required/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="قسم الوظيفة" :required="true">
            <select name="job_category_id" class="form-control form-control-solid" required>
                <option value="">اختر القسم...</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $job->job_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="المدينة / الإقليم بإيطاليا" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="مثال: ميلانو - لومبارديا" name="city" value="{{ $job->city }}"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="نوع العقد" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="نوع العقد" name="contract_type" value="{{ $job->contract_type }}"/>
        </x-form.input>

        <x-form.input class="col-md-4 col-sm-6" title="نص الراتب المعروض" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="مثال: €1,550 - €1,900" name="salary_text" value="{{ $job->salary_text }}"/>
        </x-form.input>

        <x-form.input class="col-md-4 col-sm-6" title="فترة وصافي الراتب" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="صافي شهرياً + حوافز" name="salary_period" value="{{ $job->salary_period }}"/>
        </x-form.input>

        <x-form.input class="col-md-4 col-sm-12" title="شارة التمييز / العدد المطلوب" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="مثال: مطلوب عاجل - 14 مقعداً" name="badge" value="{{ $job->badge }}"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="السكن وتفاصيله" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="السكن" name="housing" value="{{ $job->housing }}"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="الوجبات والبدلات" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="الوجبات" name="meals" value="{{ $job->meals }}"/>
        </x-form.input>

        <x-form.input class="col-md-4 col-sm-12" title="التأمين الطبي" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="التأمين" name="insurance" value="{{ $job->insurance }}"/>
        </x-form.input>

        <x-form.input class="col-md-4 col-sm-6" title="ساعات العمل والراحة" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="ساعات العمل" name="working_hours" value="{{ $job->working_hours }}"/>
        </x-form.input>

        <x-form.input class="col-md-4 col-sm-6" title="الخبرة المطلوبة" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="الخبرة" name="experience" value="{{ $job->experience }}"/>
        </x-form.input>

        <x-form.input class="col-12" title="الوصف التفصيلي للوظيفة" :required="false">
            <textarea class="form-control form-control-solid" rows="3" name="description">{{ $job->description }}</textarea>
        </x-form.input>

        <x-form.input class="col-12" title="شروط التقديم (اكتب كل شرط في سطر مستقل)" :required="false">
            <textarea class="form-control form-control-solid" rows="4" name="requirements">{{ is_array($job->requirements) ? implode("\n", $job->requirements) : $job->requirements }}</textarea>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="صورة الوظيفة" :required="false">
            <input type="file" class="form-control form-control-solid" name="image" accept="image/*"/>
            @if($job->image)
                <div class="mt-2">
                    <img src="{{ get_file($job->image) }}" class="rounded border shadow-sm" style="max-height: 70px;">
                </div>
            @endif
        </x-form.input>

        <x-form.input class="col-md-3 col-sm-6" title="الترتيب" :required="false">
            <input type="number" class="form-control form-control-solid numbersOnly" placeholder="0" name="sort_order" value="{{ $job->sort_order }}"/>
        </x-form.input>

        <x-form.input class="col-md-3 col-sm-6" title="الحالة" :required="true">
            <select name="is_active" class="form-control form-control-solid">
                <option value="1" {{ $job->is_active ? 'selected' : '' }}>مفعل</option>
                <option value="0" {{ !$job->is_active ? 'selected' : '' }}>غير مفعل</option>
            </select>
        </x-form.input>
    </div>
</form>
