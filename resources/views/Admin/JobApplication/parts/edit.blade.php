<form id="form" method="POST" action="{{ route('job-applications.update', $application->id) }}">
    @csrf
    @method('PUT')
    
    <div class="row">
        <!-- Applicant Info Summary -->
        <div class="col-12 mb-3">
            <div class="p-3 bg-light rounded border">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <small class="text-muted d-block">اسم المتقدم:</small>
                        <strong class="fs-6 text-dark">{{ $application->name }}</strong>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">رقم الهاتف / واتساب:</small>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $application->phone) }}" target="_blank" class="fw-bold text-success font-monospace dir-ltr">
                            <i class="fa fa-whatsapp mr-1"></i> {{ $application->phone }}
                        </a>
                    </div>
                    <div class="col-sm-4 mt-2">
                        <small class="text-muted d-block">العمر:</small>
                        <span>{{ $application->age ? $application->age . ' سنة' : '-' }}</span>
                    </div>
                    <div class="col-sm-4 mt-2">
                        <small class="text-muted d-block">المحافظة:</small>
                        <span>{{ $application->city?->name ?? '-' }}</span>
                    </div>
                    <div class="col-sm-4 mt-2">
                        <small class="text-muted d-block">الوظيفة المطلوبة:</small>
                        <span class="badge badge-primary px-2 py-1">{{ $application->job_title ?? ($application->job?->title ?? '-') }}</span>
                    </div>
                    <div class="col-12 mt-2">
                        <small class="text-muted d-block">موقف جواز السفر:</small>
                        <span class="badge badge-warning text-dark px-3 py-1">{{ $application->passport_situation ?? 'غير محدد' }}</span>
                    </div>
                    @if($application->notes)
                        <div class="col-12 mt-2">
                            <small class="text-muted d-block">ملاحظات وخبرات المتقدم:</small>
                            <p class="mb-0 text-secondary bg-white p-2 rounded border">{{ $application->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Status & Admin Notes -->
        <x-form.input class="col-md-6 col-sm-12" title="تحديث حالة الطلب" :required="true">
            <select name="status" class="form-control form-control-solid" required>
                <option value="pending" {{ $application->status === 'pending' ? 'selected' : '' }}>قيد الانتظار (جديد)</option>
                <option value="contacted" {{ $application->status === 'contacted' ? 'selected' : '' }}>تم التواصل هاتفياً / واتساب</option>
                <option value="accepted" {{ $application->status === 'accepted' ? 'selected' : '' }}>مقبول ومحدد موعد مقابلة</option>
                <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>مرفوض / غير مستوفي للشروط</option>
            </select>
        </x-form.input>

        <x-form.input class="col-12" title="ملاحظات الإدارة الداخلية (خاصة بالمشرفين)" :required="false">
            <textarea class="form-control form-control-solid" rows="3" placeholder="اكتب أي ملاحظات داخلية حول المتقدم أو موعد المقابلة..." name="admin_notes">{{ $application->admin_notes }}</textarea>
        </x-form.input>
    </div>
</form>
