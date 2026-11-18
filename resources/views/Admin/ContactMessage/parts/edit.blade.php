<form id="form" method="POST" action="{{ route('contact-messages.update', $message->id) }}">
    @csrf
    @method('PUT')
    
    <div class="row">
        <!-- Message Details Summary -->
        <div class="col-12 mb-3">
            <div class="p-3 bg-light rounded border">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <small class="text-muted d-block">اسم الراسل:</small>
                        <strong class="fs-6 text-dark">{{ $message->name }}</strong>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">رقم الهاتف / واتساب:</small>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $message->phone) }}" target="_blank" class="fw-bold text-success font-monospace dir-ltr">
                            <i class="fa fa-whatsapp mr-1"></i> {{ $message->phone }}
                        </a>
                    </div>
                    <div class="col-sm-6 mt-2">
                        <small class="text-muted d-block">المحافظة:</small>
                        <span class="badge badge-secondary px-2 py-1">{{ $message->city?->name ?? 'غير محددة' }}</span>
                    </div>
                    <div class="col-sm-6 mt-2">
                        <small class="text-muted d-block">الموضوع:</small>
                        <strong class="text-primary">{{ $message->subject ?? 'استفسار عام' }}</strong>
                    </div>
                    <div class="col-12 mt-3">
                        <small class="text-muted d-block">نص الرسالة والاستفسار:</small>
                        <div class="p-3 bg-white rounded border text-dark mt-1" style="white-space: pre-wrap; font-size: 0.95rem; line-height: 1.6;">{{ $message->message }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status & Notes -->
        <x-form.input class="col-md-6 col-sm-12" title="حالة القراءة والمتابعة" :required="true">
            <select name="is_read" class="form-control form-control-solid" required>
                <option value="1" {{ $message->is_read ? 'selected' : '' }}>تمت القراءة والمتابعة</option>
                <option value="0" {{ ! $message->is_read ? 'selected' : '' }}>جديدة (غير مقروءة)</option>
            </select>
        </x-form.input>

        <x-form.input class="col-12" title="ملاحظات الإدارة الداخلية" :required="false">
            <textarea class="form-control form-control-solid" rows="3" placeholder="اكتب ملاحظاتك بخصوص الرد على العميل..." name="notes">{{ $message->notes }}</textarea>
        </x-form.input>
    </div>
</form>
