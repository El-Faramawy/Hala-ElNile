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

          <div class="mb-3">
            <label for="applyName" class="form-label fw-bold small text-dark">الاسم رباعي كما في جواز السفر *</label>
            <input type="text" class="form-control rounded-3" id="applyName" placeholder="أدخل اسمك بالكامل" required>
          </div>

          <div class="mb-3">
            <label for="applyPhone" class="form-label fw-bold small text-dark">رقم الهاتف / الواتساب النشط *</label>
            <input type="tel" class="form-control rounded-3 font-monospace dir-ltr" id="applyPhone" placeholder="01xxxxxxxxx" required>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-6">
              <label for="applyAge" class="form-label fw-bold small text-dark">العمر *</label>
              <input type="number" class="form-control rounded-3" id="applyAge" min="18" max="55" placeholder="مثلاً: 28" required>
            </div>
            <div class="col-6">
              <label for="applyGov" class="form-label fw-bold small text-dark">المحافظة *</label>
              <select class="form-select rounded-3" id="applyGov">
                <option value="القاهرة">القاهرة</option>
                <option value="الجيزة">الجيزة</option>
                <option value="الإسكندرية">الإسكندرية</option>
                <option value="الشرقية">الشرقية</option>
                <option value="الغربية">الغربية</option>
                <option value="الدقهلية">الدقهلية</option>
                <option value="المنوفية">المنوفية</option>
                <option value="كفر الشيخ">كفر الشيخ</option>
                <option value="الفيوم">الفيوم</option>
                <option value="المنيا">المنيا</option>
                <option value="أسيوط">أسيوط</option>
                <option value="محافظة أخرى">محافظة أخرى</option>
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
