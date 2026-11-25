@props(['cities' => []])

<!-- ==========================================================================
     MODAL: Quick Job Apply Form
     ========================================================================== -->
<div class="modal fade" id="quickApplyModal" tabindex="-1" aria-labelledby="quickApplyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header bg-primary text-white border-0 py-3 rounded-top-4">
        <h5 class="modal-title fw-bold" id="quickApplyModalLabel">
          <i class="fa-solid fa-paper-plane me-2 text-gold"></i>
          تقديم طلب على فرصة العمل بإيطاليا
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>
      <div class="modal-body p-4">
        <div class="alert alert-info border-0 rounded-3 small mb-3">
          <i class="fa-solid fa-briefcase text-primary me-1"></i>
          الوظيفة المختارة: <strong id="applyJobTitleDisplay" class="text-dark">طلب توظيف عام</strong>
        </div>

        <form id="quickApplyForm">
          <input type="hidden" id="applyJobTitleInput" value="طلب توظيف عام">
          <input type="hidden" id="applyJobIdInput" value="">

          <x-site.form-input
            id="applyName"
            label="الاسم رباعي كما في جواز السفر *"
            placeholder="أدخل اسمك بالكامل"
            :required="true"
          />

          <div class="mb-3">
            <label for="applyPhone" class="form-label fw-bold small text-dark">رقم الهاتف / الواتساب النشط *</label>
            <div class="input-group dir-ltr">
              <span class="input-group-text bg-light text-dark fw-bold font-monospace border-end-0 px-3" style="user-select: none; cursor: not-allowed; background-color: #f1f5f9 !important;">+20</span>
              <input type="tel" class="form-control font-monospace border-start-0 text-start" id="applyPhone" name="phone" placeholder="01xxxxxxxxx" required dir="ltr">
            </div>
            <small class="text-muted" style="font-size: 11px;">كود الدولة (+20) ثابت، اكتب رقم هاتفك أو الواتساب مباشرة.</small>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-6">
              <label for="applyAge" class="form-label fw-bold small text-dark">العمر *</label>
              <input type="number" class="form-control rounded-3" id="applyAge" min="18" max="55" placeholder="مثلاً: 28" required>
            </div>
            <div class="col-6">
              <label for="applyGov" class="form-label fw-bold small text-dark">المحافظة *</label>
              <select class="form-select rounded-3" id="applyGov">
                <option value="">اختر المحافظة</option>
                @if(isset($cities) && $cities->isNotEmpty())
                  @foreach($cities as $city)
                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                  @endforeach
                @else
                  <option value="1">القاهرة</option>
                  <option value="2">الجيزة</option>
                  <option value="3">الإسكندرية</option>
                  <option value="4">الشرقية</option>
                @endif
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label for="applyPassportStatus" class="form-label fw-bold small text-dark">موقف جواز السفر *</label>
            <select class="form-select rounded-3" id="applyPassportStatus">
              <option value="يوجد جواز ساري لمدة أكثر من سنتين">يوجد جواز سفر ساري (أكثر من سنتين)</option>
              <option value="يوجد جواز ساري أقل من سنتين">يوجد جواز سفر (أقل من سنتين)</option>
              <option value="قيد الاستخراج من مصلحة الجوازات">قيد الاستخراج من الجوازات حالياً</option>
              <option value="لا يوجد وسأقوم باستخراجه">لا يوجد وسأقوم باستخراجه فوراً</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="applyNotes" class="form-label fw-bold small text-dark">المهنة السابقة أو أي ملاحظات إضافية</label>
            <textarea class="form-control rounded-3" id="applyNotes" rows="2" placeholder="اذكر خبرتك السابقة أو تفاصيل أخرى تود توضيحها"></textarea>
          </div>

          <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold">
            <i class="fa-solid fa-check-circle me-1"></i> إرسال الطلب وحجز الاستشارة
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- ==========================================================================
     MODAL: Job Details Modal (Popup)
     ========================================================================== -->
<div class="modal fade" id="jobDetailsModal" tabindex="-1" aria-labelledby="jobDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header bg-light border-bottom py-3 rounded-top-4">
        <h5 class="modal-title fw-bold" id="jobDetailsModalLabel">تفاصيل الوظيفة</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>
      <div class="modal-body p-4" id="jobDetailsModalBody">
        <!-- Populated dynamically via JS -->
      </div>
    </div>
  </div>
</div>
