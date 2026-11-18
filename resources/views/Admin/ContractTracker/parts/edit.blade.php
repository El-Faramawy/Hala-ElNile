<form id="form" method="POST" action="{{ route('contract-trackers.update', $contractTracker->id) }}">
    @csrf
    @method('PUT')
    <div class="row mt-0">
        <x-form.input class="col-md-6 col-sm-12" title="كود المعاملة" :required="true">
            <input type="text" class="form-control form-control-solid font-monospace dir-ltr" placeholder="HS-78921" name="contract_code" value="{{ $contractTracker->contract_code }}" required/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="رقم جواز السفر" :required="true">
            <input type="text" class="form-control form-control-solid font-monospace dir-ltr" placeholder="A12345678" name="passport_number" value="{{ $contractTracker->passport_number }}" required/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="اسم صاحب المعاملة" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="اسم العميل كاملاً" name="applicant_name" value="{{ $contractTracker->applicant_name }}" required/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="المسمى الوظيفي" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="الوظيفة" name="job_title" value="{{ $contractTracker->job_title }}" required/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="المدينة بإيطاليا" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="المدينة" name="city_name" value="{{ $contractTracker->city_name }}"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="المرحلة التنفيذية (من 1 إلى 5)" :required="true">
            <select name="step" class="form-control form-control-solid" required>
                <option value="1" {{ $contractTracker->step == 1 ? 'selected' : '' }}>المرحلة 1: استلام وفحص أوراق الملف</option>
                <option value="2" {{ $contractTracker->step == 2 ? 'selected' : '' }}>المرحلة 2: التقديم بنظام فلوتسي (Decreto Flussi)</option>
                <option value="3" {{ $contractTracker->step == 3 ? 'selected' : '' }}>المرحلة 3: صدور الموافقة الأمنية (Nulla Osta)</option>
                <option value="4" {{ $contractTracker->step == 4 ? 'selected' : '' }}>المرحلة 4: استلام التأشيرة من السفارة الإيطالية</option>
                <option value="5" {{ $contractTracker->step == 5 ? 'selected' : '' }}>المرحلة 5: السفر والاستقبال في إيطاليا واستلام العمل</option>
            </select>
        </x-form.input>

        <x-form.input class="col-12" title="نص رسالة الحالة (يظهر للعميل عند الاستعلام)" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="نص الحالة" name="status_text" value="{{ $contractTracker->status_text }}" required/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="تاريخ صدور المعاملة" :required="false">
            <input type="date" class="form-control form-control-solid" name="issue_date" value="{{ $contractTracker->issue_date?->format('Y-m-d') }}"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="موعد وتفاصيل السفارة (إن وجد)" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="موعد السفارة" name="embassy_appointment" value="{{ $contractTracker->embassy_appointment }}"/>
        </x-form.input>

        <x-form.input class="col-12" title="تفاصيل رحلة الطيران والاستقبال" :required="false">
            <textarea class="form-control form-control-solid" rows="2" name="flight_details">{{ $contractTracker->flight_details }}</textarea>
        </x-form.input>

        <x-form.input class="col-12" title="تعليمات وإرشادات لصاحب العقد" :required="false">
            <textarea class="form-control form-control-solid" rows="2" name="notes">{{ $contractTracker->notes }}</textarea>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="الحالة" :required="true">
            <select name="is_active" class="form-control form-control-solid">
                <option value="1" {{ $contractTracker->is_active ? 'selected' : '' }}>مفعل (يظهر بالاستعلام)</option>
                <option value="0" {{ !$contractTracker->is_active ? 'selected' : '' }}>غير مفعل</option>
            </select>
        </x-form.input>
    </div>
</form>
