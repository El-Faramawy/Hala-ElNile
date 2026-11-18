<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('jobs.store') }}">
    @csrf
    <div class="row mt-0">
        <x-form.input class="col-md-6 col-sm-12" title="المسمى الوظيفي" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="مثال: مساعد شيف وطهاة مطاعم إيطالية" name="title" required/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="قسم الوظيفة" :required="true">
            <select name="job_category_id" class="form-control form-control-solid" required>
                <option value="">اختر القسم...</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="المدينة / الإقليم بإيطاليا" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="مثال: ميلانو - لومبارديا" name="city"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="نوع العقد" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="مثال: عقد دائم (غير موسمي) / فلوتسي 9 أشهر" name="contract_type"/>
        </x-form.input>

        <x-form.input class="col-md-4 col-sm-6" title="نص الراتب المعروض" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="مثال: €1,550 - €1,900" name="salary_text"/>
        </x-form.input>

        <x-form.input class="col-md-4 col-sm-6" title="فترة وصافي الراتب" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="صافي شهرياً + حوافز" name="salary_period" value="صافي شهرياً + حوافز"/>
        </x-form.input>

        <x-form.input class="col-md-4 col-sm-12" title="شارة التمييز / العدد المطلوب" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="مثال: مطلوب عاجل - 14 مقعداً" name="badge"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="السكن وتفاصيله" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="سكن مؤثث مجاني شامل الفواتير" name="housing"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="الوجبات والبدلات" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="وجبات طعام يومية أثناء العمل" name="meals"/>
        </x-form.input>

        <x-form.input class="col-md-4 col-sm-12" title="التأمين الطبي" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="تأمين صحي إيطالي شامل (SSN)" name="insurance"/>
        </x-form.input>

        <x-form.input class="col-md-4 col-sm-6" title="ساعات العمل والراحة" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="8 ساعات يومياً - يوم ونصف راحة" name="working_hours"/>
        </x-form.input>

        <x-form.input class="col-md-4 col-sm-6" title="الخبرة المطلوبة" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="خبرة سنتين / لا يشترط خبرة" name="experience"/>
        </x-form.input>

        <x-form.input class="col-12" title="الوصف التفصيلي للوظيفة" :required="false">
            <textarea class="form-control form-control-solid" rows="3" placeholder="اكتب وصفاً مفصلاً لطبيعة العمل ومكانه..." name="description"></textarea>
        </x-form.input>

        <x-form.input class="col-12" title="شروط التقديم (اكتب كل شرط في سطر مستقل)" :required="false">
            <textarea class="form-control form-control-solid" rows="4" placeholder="جواز سفر ساري لمدة سنتين على الأقل&#10;شهادة صحية معتمدة وخلو من الأمراض&#10;الاستعداد لتعلم أساسيات الإيطالية" name="requirements"></textarea>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="صورة الوظيفة" :required="false">
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
