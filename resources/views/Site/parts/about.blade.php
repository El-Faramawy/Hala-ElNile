<!-- ==========================================================================
     About Company & Official Licensing Section
     ========================================================================== -->
<section class="py-5" id="about">
  <div class="container py-lg-4">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <div class="position-relative reveal-on-scroll">
          <!-- Colosseum & Italian landmark graphic with badge -->
          <div class="rounded-4 overflow-hidden shadow-lg border">
            <img src="{{ asset('Site/assets/images/hero/slide-agency.jpg') }}" alt="مقر وشراكات هلا النيل في إيطاليا" class="w-100 object-fit-cover about-main-img" loading="lazy" decoding="async">
          </div>
          <!-- Trust Floater Card -->
          <div class="position-absolute bottom-0 start-0 m-4 p-3 bg-white rounded-3 shadow-lg border d-flex align-items-center gap-3" style="max-width: 320px;">
            <div class="p-3 bg-success text-white rounded-3 fs-3">
              <i class="fa-solid fa-stamp"></i>
            </div>
            <div>
              <h6 class="fw-bold mb-1 text-dark">ترخيص قوى عاملة 912</h6>
              <p class="small text-muted mb-0">سجل تجاري 104522 بطاقة ضريبية معتمدة وقائمة بيضاء رسمية.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="section-header text-start mb-4 reveal-on-scroll">
          <span class="section-subtitle"><i class="fa-solid fa-building-columns me-1"></i> من نحن</span>
          <h2 class="section-title">
            شركة هلا النيل لإلحاق العمالة بالخارج
          </h2>
          <p class="section-desc text-start mx-0">
            تأسست شركة <strong>هلا النيل لإلحاق العمالة المصرية بالخارج</strong> بموجب ترخيص رسمي صادر من وزارة القوى العاملة والهجرة المصرية برقم <strong>(912 شركات)</strong>. نحن متخصصون حصرياً في تأمين وتنظيم فرص العمل الرسمية والشرعية داخل دولة إيطاليا، بالشراكة مع كبرى الاتحادات العمالية والغرف التجارية الإيطالية.
          </p>
        </div>

        <div class="row g-3 mb-4">
          <x-site.about-feature
            :stagger="1"
            title="اعتماد القنصلية الإيطالية"
            description="كافة عقودنا وتأشيراتنا تصدر مباشرة عبر القنوات القنصلية الرسمية بالقاهرة والإسكندرية."
          />

          <x-site.about-feature
            :stagger="2"
            title="شراكات عمل حقيقية"
            description="عقود مباشرة مع أصحاب الأعمال في لومبارديا، لاتسيو، إميليا رومانيا وتوسكانا."
          />

          <x-site.about-feature
            :stagger="3"
            title="لا رسوم قبل الموافقة"
            description="نلتزم بالحدود القانونية للرسوم وفقاً للوائح وزارة القوى العاملة وبدون أي مبالغات."
          />

          <x-site.about-feature
            :stagger="4"
            title="رعاية واستقبال بإيطاليا"
            description="فريقنا ومندوبونا في مطارات روما وميلانو يرافقونك حتى استلام السكن وبدء العمل."
          />
        </div>

        <div class="d-flex flex-wrap gap-3">
          <a href="#why-us" class="btn btn-primary px-4 py-3 rounded-pill">
            <i class="fa-solid fa-circle-check me-1"></i> استكشف مميزاتنا وضماناتك
          </a>
          @if(!empty($settings?->whatsapp))
            @php $waNum = preg_replace('/[^0-9]/', '', $settings->whatsapp); @endphp
            <a href="https://wa.me/{{ $waNum }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary px-4 py-3 rounded-pill">
              <i class="fa-brands fa-whatsapp text-success fs-5 me-1"></i> تواصل مع المدير التنفيذي
            </a>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>
