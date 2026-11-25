<form id="form" method="POST" action="{{ route('job-applications.store') }}">
    @csrf
    <div class="row mt-0">
        <x-form.input class="col-md-6 col-sm-12" title="الاسم رباعي للمتقدم" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="مثال: أحمد محمد علي حسن" name="name" required/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="رقم الهاتف / الواتساب" :required="true">
            <input type="text" class="form-control form-control-solid font-monospace dir-ltr" placeholder="01xxxxxxxxx" name="phone" required/>
        </x-form.input>

        <x-form.input class="col-md-3 col-sm-6" title="العمر" :required="false">
            <input type="number" class="form-control form-control-solid numbersOnly" placeholder="مثال: 28" name="age" min="18" max="70"/>
        </x-form.input>

        <x-form.input class="col-md-3 col-sm-6" title="المحافظة" :required="false">
            <select name="city_id" class="form-control form-control-solid">
                <option value="">اختر المحافظة...</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                @endforeach
            </select>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="فرصة العمل المتاحة" :required="false">
            <select name="job_id" id="job_id_select" class="form-control form-control-solid">
                <option value="">اختر الوظيفة من قاعدة البيانات...</option>
                @foreach($jobs as $job)
                    <option value="{{ $job->id }}" data-title="{{ $job->title }}">{{ $job->title }} ({{ $job->city ?? 'إيطاليا' }})</option>
                @endforeach
            </select>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="أو مسمى وظيفي حر (إذا لم تكن محددة)" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="مثال: مساعد شيف أو عمالة زراعية عامة" name="job_title" id="custom_job_title"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="موقف جواز السفر" :required="false">
            <select name="passport_situation" class="form-control form-control-solid">
                <option value="يوجد جواز ساري لمدة أكثر من سنتين" selected>يوجد جواز سفر ساري (أكثر من سنتين)</option>
                <option value="يوجد جواز ساري أقل من سنتين">يوجد جواز سفر (أقل من سنتين)</option>
                <option value="قيد الاستخراج من مصلحة الجوازات">قيد الاستخراج من الجوازات حالياً</option>
                <option value="لا يوجد وسأقوم باستخراجه">لا يوجد وسأقوم باستخراجه فوراً</option>
            </select>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="رقم جواز السفر (إن وجد)" :required="false">
            <input type="text" class="form-control form-control-solid font-monospace dir-ltr" placeholder="مثال: A12345678" name="passport_number"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="المرحلة / حالة الطلب" :required="true">
            <select name="status" id="app_status_select" class="form-control form-control-solid" required>
                <option value="new" selected>طلب جديد (بانتظار الفحص الأولي)</option>
                <option value="stage_1">المرحلة 1: استلام وفحص أوراق الملف</option>
                <option value="stage_2">المرحلة 2: اعتماد العقد والتسجيل بنظام فلوتسي (Decreto Flussi)</option>
                <option value="stage_3">المرحلة 3: صدور تصريح العمل والموافقة الأمنية (Nulla Osta)</option>
                <option value="stage_4">المرحلة 4: حجز البصمة واستلام التأشيرة من السفارة</option>
                <option value="stage_5">المرحلة 5: السفر والاستقبال في إيطاليا واستلام العمل</option>
                <option value="contacted">تم التواصل هاتفياً / واتساب مع المتقدم</option>
                <option value="cancelled">ملغي (يتطلب كتابة سبب الإلغاء)</option>
            </select>
        </x-form.input>

        <div class="col-12" id="cancellation_reason_box" style="display: none;">
            <x-form.input class="col-12" title="سبب الإلغاء (إجباري في حالة الإلغاء)" :required="true">
                <textarea class="form-control form-control-solid" rows="2" placeholder="العميل لم يدفع المبلغ بعد الموافقة على العقد" name="cancellation_reason" id="cancellation_reason_input"></textarea>
            </x-form.input>
        </div>

        <x-form.input class="col-12" title="ملاحظات المتقدم وخبراته السابقة" :required="false">
            <textarea class="form-control form-control-solid" rows="2" placeholder="سجل أي تفاصيل ذكرها المتقدم حول خبراته..." name="notes"></textarea>
        </x-form.input>

        <x-form.input class="col-12" title="ملاحظات الإدارة الداخلية (خاصة بالمشرفين)" :required="false">
            <textarea class="form-control form-control-solid" rows="2" placeholder="اكتب أي ملاحظات داخلية لفريق العمل..." name="admin_notes"></textarea>
        </x-form.input>
    </div>
</form>

<script>
    (function () {
        var statusSelect = document.getElementById('app_status_select');
        var cancelBox = document.getElementById('cancellation_reason_box');
        var cancelInput = document.getElementById('cancellation_reason_input');

        function toggleCancellation() {
            if (statusSelect && cancelBox) {
                if (statusSelect.value === 'cancelled' || statusSelect.value === 'rejected') {
                    cancelBox.style.display = 'block';
                    if (cancelInput) cancelInput.setAttribute('required', 'required');
                } else {
                    cancelBox.style.display = 'none';
                    if (cancelInput) cancelInput.removeAttribute('required');
                }
            }
        }

        if (statusSelect) {
            statusSelect.addEventListener('change', toggleCancellation);
            toggleCancellation();
        }

        var jobSelect = document.getElementById('job_id_select');
        var customTitle = document.getElementById('custom_job_title');
        if (jobSelect && customTitle) {
            jobSelect.addEventListener('change', function () {
                var selectedOption = jobSelect.options[jobSelect.selectedIndex];
                if (selectedOption && selectedOption.dataset.title && !customTitle.value) {
                    customTitle.value = selectedOption.dataset.title;
                }
            });
        }
    })();
</script>
