<!-- ==========================================================================
     Interactive Contract & Visa Tracker Section (Exclusive Feature)
     ========================================================================== -->
<section class="py-5" id="tracker">
  <div class="container py-lg-3">
    <div class="tracker-box reveal-on-scroll">
      <div class="row align-items-center g-4">
        <div class="col-lg-6">
          <span class="badge bg-gold text-dark fw-bold px-3 py-2 rounded-pill mb-3">
            <i class="fa-solid fa-magnifying-glass me-1"></i> تتبع طلبك
          </span>
          <h2 class="fw-bold text-white mb-3">متابعة حالة العقد والتأشيرة لحظة بلحظة</h2>
          <p class="text-white-50 mb-4">
            يمكنك متابعة حالة طلبك أو فحص رقم الموافقة المبدئية (Nulla Osta) وموعد بصمة السفارة الإيطالية بكل شفافية وسهولة بإدخال رقم جواز السفر أو كود المعاملة الخاص بك.
          </p>
          <div class="small text-white-50">
            <i class="fa-solid fa-lightbulb text-warning me-1"></i> تجربة استعلام تجريبية: جرّب إدخال <strong class="text-white font-monospace">A12345678</strong> أو <strong class="text-white font-monospace">HS-99881</strong> أو أي رقم جواز سفر!
          </div>
        </div>

        <div class="col-lg-6">
          <div class="tracker-input-group d-flex align-items-center">
            <input type="text" id="trackPassportInput" class="form-control" placeholder="أدخل رقم جواز السفر (مثال: A12345678) أو كود الطلب...">
            <button class="btn btn-primary px-4 py-3 rounded-pill flex-shrink-0" id="btnTrackContract">
              <i class="fa-solid fa-magnifying-glass me-1"></i> استعلام فوري
            </button>
          </div>
        </div>
      </div>

      <!-- Result Container -->
      <div id="trackResultContainer" class="d-none"></div>
    </div>
  </div>
</section>
