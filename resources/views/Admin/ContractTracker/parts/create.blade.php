<form id="form" method="POST" action="{{ route('contract-trackers.store') }}">
    @csrf
    <div class="row mt-0">
        <x-form.input class="col-md-6 col-sm-12" title="كود المعاملة" :required="true">
            <input type="text" class="form-control form-control-solid font-monospace dir-ltr" placeholder="مثال: HS-78921" name="contract_code" required/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="رقم جواز السفر" :required="true">
            <input type="text" class="form-control form-control-solid font-monospace dir-ltr" placeholder="مثال: A12345678" name="passport_number" required/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="اسم صاحب المعاملة" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="اسم العميل كاملاً" name="applicant_name" required/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="المسمى الوظيفي" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="مثال: مساعد شيف - ميلانو" name="job_title" required/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="المدينة بإيطاليا" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="مثال: ميلانو / روما / فلورنسا" name="city_name"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="المرحلة التنفيذية (من 1 إلى 5)" :required="true">
            <select name="step" class="form-control form-control-solid" required>
                <option value="1">المرحلة 1: استلام وفحص أوراق الملف</option>
                <option value="2">المرحلة 2: التقديم بنظام فلوتسي (Decreto Flussi)</option>
                <option value="3">المرحلة 3: صدور الموافقة الأمنية (Nulla Osta)</option>
                <option value="4">المرحلة 4: استلام التأشيرة من السفارة الإيطالية</option>
                <option value="5">المرحلة 5: السفر والاستقبال في إيطاليا واستلام العمل</option>
            </select>
        </x-form.input>

        <x-form.input class="col-12" title="نص رسالة الحالة (يظهر للعميل عند الاستعلام)" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="مثال: تم إصدار الموافقة الأمنية (Nulla Osta) بنجاح" name="status_text" required/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="تاريخ صدور المعاملة" :required="false">
            <input type="date" class="form-control form-control-solid" name="issue_date"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="موعد وتفاصيل السفارة (إن وجد)" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="مثال: 15 نوفمبر 2026 - السفارة الإيطالية بجاردن سيتي" name="embassy_appointment"/>
        </x-form.input>

        <x-form.input class="col-12" title="تفاصيل رحلة الطيران والاستقبال" :required="false">
            <textarea class="form-control form-control-solid" rows="2" placeholder="بيانات الرحلة ومندوب الاستقبال بمطار روما أو ميلانو..." name="flight_details"></textarea>
        </x-form.input>

        <x-form.input class="col-12" title="تعليمات وإرشادات لصاحب العقد" :required="false">
            <textarea class="form-control form-control-solid" rows="2" placeholder="المستندات المطلوبة إحضارها، تعليمات البصمة..." name="notes"></textarea>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="الحالة" :required="true">
            <select name="is_active" class="form-control form-control-solid">
                <option value="1">مفعل (يظهر بالاستعلام)</option>
                <option value="0">غير مفعل</option>
            </select>
        </x-form.input>
    </div>
</form>
