<form id="form" method="POST" action="{{ route('job-applications.update', $application->id) }}">
    @csrf
    @method('PUT')

    <div class="row">
        <!-- Application Summary Header -->
        <div class="col-12 mb-3">
            <div class="p-3 bg-light rounded border">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 border-bottom pb-2 mb-2">
                    <div>
                        <span class="text-muted small">كود المعاملة:</span>
                        <span class="badge badge-primary font-monospace fs-14 px-2 py-1">#{{ $application->code ?? $application->id }}</span>
                        @if($application->contract_tracker_id)
                            <span class="badge badge-success px-2 py-1 mr-1">
                                <i class="fa fa-check-circle mr-1"></i> عقد ساري (#{{ $application->contractTracker?->contract_code }})
                            </span>
                        @endif
                    </div>
                    <div>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $application->phone) }}" target="_blank" class="btn btn-sm btn-success font-monospace dir-ltr">
                            <i class="fa fa-whatsapp mr-1"></i> {{ $application->phone }}
                        </a>
                    </div>
                </div>

                <!-- Stage Timeline Stepper -->
                <div class="mt-2">
                    <label class="fw-bold small text-dark mb-2">
                        <i class="fa fa-history text-primary mr-1"></i> السجل الزمني لمراحل وتاريخ الطلب:
                    </label>
                    @if(!empty($application->stage_history) && is_array($application->stage_history))
                        <div class="timeline-wrapper bg-white p-3 rounded border" style="max-height: 180px; overflow-y: auto;">
                            <ul class="list-unstyled mb-0 position-relative">
                                @foreach($application->stage_history as $index => $history)
                                    <li class="d-flex align-items-start mb-2 {{ !$loop->last ? 'border-bottom pb-2' : '' }}">
                                        <span class="badge {{ $history['stage'] === 'cancelled' || $history['stage'] === 'rejected' ? 'badge-danger' : 'badge-primary' }} rounded-circle p-2 mt-1 mr-2" style="width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 11px;">
                                            {{ $index + 1 }}
                                        </span>
                                        <div class="flex-grow-1 pr-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong class="text-dark small">{{ $history['title'] ?? $history['stage'] }}</strong>
                                                <span class="text-muted font-monospace" style="font-size: 11px;">
                                                    <i class="fa fa-calendar-o mr-1"></i> {{ $history['changed_at'] ?? '-' }}
                                                </span>
                                            </div>
                                            <div class="text-secondary small mt-1">
                                                <span class="text-muted">المسؤول:</span> {{ $history['admin_name'] ?? 'النظام' }}
                                                @if(!empty($history['notes']))
                                                    | <span class="text-muted">ملاحظة:</span> {{ $history['notes'] }}
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="alert alert-secondary py-2 px-3 small mb-0">
                            لا يوجد سجل مراحل مسبق، سيتم تدشين السجل مع أول تحديث.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Editable Applicant Details -->
        <div class="col-12 mb-2">
            <h6 class="text-primary font-weight-bold mb-2 pb-1 border-bottom">
                <i class="fa fa-user-circle mr-1"></i> بيانات المتقدم (يمكن التعديل عليها):
            </h6>
        </div>

        <x-form.input class="col-md-6 col-sm-12" title="اسم المتقدم رباعي" :required="true">
            <input type="text" class="form-control form-control-solid" name="name" value="{{ $application->name }}" required/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="رقم الهاتف / الواتساب" :required="true">
            <input type="text" class="form-control form-control-solid font-monospace dir-ltr" name="phone" value="{{ $application->phone }}" required/>
        </x-form.input>

        <x-form.input class="col-md-3 col-sm-6" title="العمر" :required="false">
            <input type="number" class="form-control form-control-solid numbersOnly" name="age" value="{{ $application->age }}" min="18" max="70"/>
        </x-form.input>

        <x-form.input class="col-md-3 col-sm-6" title="المحافظة" :required="false">
            <select name="city_id" class="form-control form-control-solid">
                <option value="">اختر المحافظة...</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}" {{ $application->city_id === $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                @endforeach
            </select>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="فرصة العمل المتاحة" :required="false">
            <select name="job_id" id="edit_job_id_select" class="form-control form-control-solid">
                <option value="">اختر الوظيفة...</option>
                @foreach($jobs as $job)
                    <option value="{{ $job->id }}" {{ $application->job_id === $job->id ? 'selected' : '' }}>{{ $job->title }} ({{ $job->city ?? 'إيطاليا' }})</option>
                @endforeach
            </select>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="المسمى الوظيفي المسجل" :required="false">
            <input type="text" class="form-control form-control-solid" name="job_title" value="{{ $application->job_title ?? ($application->job?->title ?? '') }}"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="موقف جواز السفر" :required="false">
            <select name="passport_situation" class="form-control form-control-solid">
                <option value="يوجد جواز ساري لمدة أكثر من سنتين" {{ $application->passport_situation === 'يوجد جواز ساري لمدة أكثر من سنتين' ? 'selected' : '' }}>يوجد جواز سفر ساري (أكثر من سنتين)</option>
                <option value="يوجد جواز ساري أقل من سنتين" {{ $application->passport_situation === 'يوجد جواز ساري أقل من سنتين' ? 'selected' : '' }}>يوجد جواز سفر (أقل من سنتين)</option>
                <option value="قيد الاستخراج من مصلحة الجوازات" {{ $application->passport_situation === 'قيد الاستخراج من مصلحة الجوازات' ? 'selected' : '' }}>قيد الاستخراج من الجوازات حالياً</option>
                <option value="لا يوجد وسأقوم باستخراجه" {{ $application->passport_situation === 'لا يوجد وسأقوم باستخراجه' ? 'selected' : '' }}>لا يوجد وسأقوم باستخراجه فوراً</option>
            </select>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="رقم جواز السفر" :required="false">
            <input type="text" class="form-control form-control-solid font-monospace dir-ltr" name="passport_number" value="{{ $application->passport_number }}" placeholder="مثال: A12345678"/>
        </x-form.input>

        <x-form.input class="col-12" title="ملاحظات وخبرات المتقدم السابقة" :required="false">
            <textarea class="form-control form-control-solid" rows="2" name="notes">{{ $application->notes }}</textarea>
        </x-form.input>

        <!-- Admin Management Section -->
        <div class="col-12 mt-3 mb-2">
            <h6 class="text-primary font-weight-bold mb-2 pb-1 border-bottom">
                <i class="fa fa-cog mr-1"></i> إدارة المرحلة وحالة الطلب:
            </h6>
        </div>

        <x-form.input class="col-md-6 col-sm-12" title="تحديث مرحلة / حالة الطلب" :required="true">
            <select name="status" id="edit_app_status_select" class="form-control form-control-solid" required>
                <option value="new" {{ $application->status === 'new' ? 'selected' : '' }}>طلب جديد (وارد من الموقع / بانتظار الفحص)</option>
                <option value="stage_1" {{ in_array($application->status, ['stage_1', 'pending'], true) ? 'selected' : '' }}>المرحلة 1: استلام وفحص أوراق الملف</option>
                <option value="stage_2" {{ in_array($application->status, ['stage_2', 'accepted'], true) ? 'selected' : '' }}>المرحلة 2: اعتماد العقد والتقديم بفلوتسي (Decreto Flussi)</option>
                <option value="stage_3" {{ $application->status === 'stage_3' ? 'selected' : '' }}>المرحلة 3: صدور تصريح العمل والموافقة الأمنية (Nulla Osta)</option>
                <option value="stage_4" {{ $application->status === 'stage_4' ? 'selected' : '' }}>المرحلة 4: حجز البصمة واستلام التأشيرة من السفارة</option>
                <option value="stage_5" {{ $application->status === 'stage_5' ? 'selected' : '' }}>المرحلة 5: السفر والاستقبال في إيطاليا واستلام العمل</option>
                <option value="contacted" {{ $application->status === 'contacted' ? 'selected' : '' }}>تم التواصل هاتفياً / واتساب مع المتقدم</option>
                <option value="cancelled" {{ in_array($application->status, ['cancelled', 'rejected'], true) ? 'selected' : '' }}>ملغي (إجباري سبب الإلغاء)</option>
            </select>
        </x-form.input>

        <div class="col-12" id="edit_cancellation_reason_box" style="{{ in_array($application->status, ['cancelled', 'rejected'], true) ? '' : 'display: none;' }}">
            <x-form.input class="col-12" title="سبب الإلغاء (إجباري في حالة الإلغاء)" :required="true">
                <textarea class="form-control form-control-solid" rows="2" placeholder="العميل لم يدفع المبلغ بعد الموافقة على العقد" name="cancellation_reason" id="edit_cancellation_reason_input">{{ $application->cancellation_reason }}</textarea>
            </x-form.input>
        </div>

        <x-form.input class="col-12" title="ملاحظات الإدارة الداخلية (خاصة بالمشرفين)" :required="false">
            <textarea class="form-control form-control-solid" rows="3" placeholder="اكتب أي ملاحظات داخلية حول المتقدم أو موعد المقابلة..." name="admin_notes">{{ $application->admin_notes }}</textarea>
        </x-form.input>
    </div>
</form>

<script>
    (function () {
        var statusSelect = document.getElementById('edit_app_status_select');
        var cancelBox = document.getElementById('edit_cancellation_reason_box');
        var cancelInput = document.getElementById('edit_cancellation_reason_input');

        function toggleEditCancellation() {
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
            statusSelect.addEventListener('change', toggleEditCancellation);
            toggleEditCancellation();
        }
    })();
</script>
